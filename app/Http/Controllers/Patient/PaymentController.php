<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CheckBlockedUser;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', RoleMiddleware::class . ':patient', CheckBlockedUser::class]);
    }
    // Affiche le formulaire de paiement
    public function showForm()
    {
        // Vérifier que l'utilisateur est bien un patient
        if (!auth()->check() || auth()->user()->role !== 'patient') {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter en tant que patient pour accéder à cette page.');
        }
        // Récupérer le pack et le montant depuis la requête
        $pack = request()->query('pack', 'standard');
        $montant = (int)request()->query('montant', 5000);
        
        // Valider le pack
        $packsValides = ['standard', 'premium', 'expert'];
        if (!in_array($pack, $packsValides)) {
            $pack = 'standard';
        }
        
        // Valider le montant
        if ($montant < 100) {
            $montant = 5000; // Montant par défaut
        }
        
        // Récupérer la liste des médecins
        $medecins = \App\Models\User::where('role', 'medecin')
            ->orderBy('name')
            ->get(['id', 'name', 'specialite']);
            
        return view('patient.paiement.form', [
            'pack' => $pack,
            'montant' => $montant,
            'medecins' => $medecins
        ]);
    }

    // Lance le paiement via Noupia Pay
    public function process(Request $request)
    {
        // Nettoyer le numéro de téléphone avant la validation
        if ($request->has('phone')) {
            $request->merge(['phone' => preg_replace('/\D/', '', $request->phone)]);
        }

        $validated = $request->validate([
            'phone' => [
                'required',
                'regex:/^[23679]\d{8}$/',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[23679]\d{8}$/', $value)) {
                        $fail('Le numéro de téléphone doit être un numéro camerounais valide (9 chiffres commençant par 2, 3, 6, 7 ou 9)');
                    }
                },
            ],
            'operator' => 'required|in:orange,mtn',
            'amount' => 'required|numeric|min:100',
            'pack' => 'required|in:standard,premium,expert',
            'medecin_id' => 'required|exists:users,id',
        ], [
            'phone.regex' => 'Le numéro de téléphone doit être un numéro camerounais valide (9 chiffres commençant par 2, 3, 6, 7 ou 9)',
            'phone.required' => 'Le numéro de téléphone est obligatoire',
            'operator.required' => 'Veuillez sélectionner un opérateur',
            'operator.in' => 'Opérateur invalide',
        ]);
        
        // Vérifier que le médecin existe et qu'il est bien un médecin
        $medecin = \App\Models\User::where('id', $request->medecin_id)
            ->where('role', 'medecin')
            ->first();
            
        if (!$medecin) {
            return back()->with('error', 'Le médecin sélectionné n\'est pas disponible.')->withInput();
        }

        // Préparation des paramètres pour l'API Noupia Pay
        $apiUrl = 'https://api.noupia.com/pay';
        $apiKey = config('services.noupia.api_key');
        $productKey = config('services.noupia.product_key');
        $reference = 'ABON_' . strtoupper(substr($request->pack, 0, 3)) . '_' . strtoupper(uniqid());
        
        // Stocker les informations en session pour la vérification
        $request->session()->put('abonnement_data', [
            'pack' => $request->pack,
            'montant' => $request->amount,
            'medecin_id' => $request->medecin_id,
            'reference' => $reference,
        ]);
        $method = 'mobilemoney';
        $country = 'CM';
        $currency = 'XAF';
        $email = Auth::user()->email;
        $name = Auth::user()->name;
        $signature = 'np-live';

        $postData = [
            'operation' => 'initiate',
            'reference' => $reference,
            'amount' => (int) $request->amount,
            'phone' => $request->phone,
            'method' => $method,
            'country' => $country,
            'currency' => $currency,
            'email' => $email,
            'name' => $name,
        ];
        
        // Journaliser les données de la requête
        \Log::info('Requête Noupia Pay', [
            'url' => $apiUrl,
            'data' => $postData,
            'pack' => $request->pack,
            'medecin_id' => $request->medecin_id
        ]);

        $noupiaConfig = config('services.noupia');
        
        // Préparer les en-têtes selon la documentation de l'API Noupia
        $apiSignature = $noupiaConfig['mode'] === 'live' ? 'live' : 'test';
        
        $curlHeaders = [
            'Accept: application/json',
            'Content-Type: application/json',
            'Noupia-API-Signature: ' . $apiSignature,
            'Noupia-API-Key: ' . $noupiaConfig['api_key'],
            'Noupia-Product-Key: ' . $noupiaConfig['product_key'],
            'Cache-Control: no-cache',
            'Connection: keep-alive',
            'User-Agent: DermOnline/1.0',
            'Accept-Language: fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7',
            'X-Requested-With: XMLHttpRequest',
            'X-Noupia-Request-ID: ' . uniqid(),
            'X-Noupia-Country: CM',
            'X-Noupia-Currency: XAF'
        ];
        
        // Journaliser les en-têtes pour débogage (sans les clés sensibles)
        $loggableHeaders = array_map(function($header) {
            if (stripos($header, 'Noupia-API-Key') !== false || 
                stripos($header, 'Noupia-Product-Key') !== false) {
                $parts = explode(':', $header, 2);
                return $parts[0] . ': ********';
            }
            return $header;
        }, $curlHeaders);
        
        \Log::info('Envoi de la requête à Noupia Pay', [
            'url' => $apiUrl,
            'headers' => $loggableHeaders,
            'body' => $postData
        ]);
        
        // Vérifier que tous les en-têtes requis sont présents
        $requiredHeaders = [
            'Noupia-API-Signature',
            'Noupia-API-Key',
            'Noupia-Product-Key'
        ];
        
        $headersStr = implode('|', $curlHeaders);
        foreach ($requiredHeaders as $header) {
            if (strpos($headersStr, $header) === false) {
                \Log::error("En-tête manquant dans la configuration Noupia : $header");
                return back()->with('error', "Erreur de configuration du système de paiement (en-tête manquant : $header).");
            }
        }

        // Activer le suivi des en-têtes de requête
        $logFile = storage_path('logs/noupia_requests.log');
        $fh = fopen($logFile, 'a+');
        
        // Configuration cURL avec gestion d'erreur améliorée
        $ch = curl_init();
        
        // Activer le débogage détaillé
        $verboseLog = fopen('php://temp', 'w+');
        
        // Options de cURL
        $options = [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_SSL_VERIFYPEER => false, // À activer en production
            CURLOPT_VERBOSE => true,
            CURLOPT_STDERR => $verboseLog,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ];
        
        curl_setopt_array($ch, $options);
        
        // Exécution de la requête
        $response = curl_exec($ch);
        
        // Récupération des informations de débogage
        $info = curl_getinfo($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        
        // Journalisation détaillée
        rewind($verboseLog);
        $verboseLogs = stream_get_contents($verboseLog);
        
        // Fermeture des ressources
        curl_close($ch);
        fclose($verboseLog);
        
        // Journalisation des informations de la requête
        $logData = [
            'request' => [
                'url' => $apiUrl,
                'method' => 'POST',
                'headers' => $loggableHeaders,
                'body' => $postData
            ],
            'response' => [
                'http_code' => $info['http_code'] ?? null,
                'error' => $error,
                'errno' => $errno,
                'verbose' => $verboseLogs
            ]
        ];
        
        // Journalisation dans un fichier dédié
        file_put_contents(
            storage_path('logs/noupia_requests.log'),
            json_encode($logData, JSON_PRETTY_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n\n",
            FILE_APPEND
        );
        
        // Gestion des erreurs cURL
        if ($errno) {
            \Log::error('Erreur cURL lors de la requête vers Noupia', [
                'error' => $error,
                'errno' => $errno,
                'url' => $apiUrl
            ]);
            return back()->with('error', 'Erreur lors de la connexion au service de paiement. Veuillez réessayer plus tard.');
        }
        
        // Extraire les en-têtes et le corps de la réponse
        $headerSize = $info['header_size'];
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        // Journaliser la réponse complète
        \Log::info('Réponse de Noupia Pay', [
            'http_code' => $info['http_code'],
            'headers' => $headers,
            'body' => $body,
            'info' => $info
        ]);
        
        // Vérifier le code HTTP
        if ($info['http_code'] !== 200) {
            $errorMessage = "Erreur HTTP {$info['http_code']} lors de la communication avec le service de paiement.";
            
            // Essayer d'extraire un message d'erreur plus détaillé
            $responseData = json_decode($body, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($responseData['message'])) {
                $errorMessage = $responseData['message'];
                
                // Traduction des messages d'erreur courants
                $errorMessages = [
                    'missing header in request' => 'En-tête manquant dans la requête. Veuillez contacter le support.',
                    'invalid api key' => 'Clé API invalide. Veuillez vérifier la configuration.',
                    'insufficient funds' => 'Fonds insuffisants sur votre compte de paiement.'
                ];
                
                foreach ($errorMessages as $key => $message) {
                    if (stripos(strtolower($errorMessage), $key) !== false) {
                        $errorMessage = $message;
                        break;
                    }
                }
            }
            
            return back()->with('error', $errorMessage)->withInput();
        }

        $result = json_decode($body, true); // Convertir en tableau associatif
        
        // Vérifier si la réponse est valide
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Réponse invalide du serveur de paiement.');
        }

        // Vérifier si la réponse indique une erreur
        if (!isset($result['response']) || $result['response'] !== 'success') {
            $message = $result['message'] ?? 'Erreur inconnue lors du paiement.';
            \Log::error('Erreur de paiement Noupia', ['response' => $result]);
            return back()->with('error', $message);
        }

        // Paiement initié avec succès
        $transactionId = $result['data']['transaction'] ?? null;
        $ussd = $result['data']['channel_ussd'] ?? null;
        $channel = $result['data']['channel_name'] ?? null;
        
        // Vérifier que l'ID de transaction est présent
        if (!$transactionId) {
            \Log::error('ID de transaction manquant dans la réponse', ['response' => $result]);
            return back()->with('error', 'Erreur lors de la récupération des informations de transaction.');
        }

        // Stocker transactionId en session pour vérification ultérieure
        session([
            'noupia_transaction_id' => $transactionId,
            'noupia_reference' => $reference,
            'noupia_amount' => $request->amount,
        ]);

        return redirect()->route('patient.paiement.verify')->with('info', "Paiement initié. Veuillez valider sur votre téléphone ($channel, USSD: $ussd). Après validation, cliquez sur 'Vérifier le paiement'.");
    }

    // Vérifie le statut du paiement
    public function verify(Request $request)
    {
        $transactionId = session('noupia_transaction_id');
        $reference = session('noupia_reference');
        $amount = session('noupia_amount');
        $user = Auth::user();

        if (!$transactionId) {
            return redirect()->route('patient.paiement.form')->with('error', 'Aucune transaction à vérifier.');
        }

        // Appel API Noupia pour vérifier le paiement
        $apiUrl = 'https://api.noupia.com/pay';
        $apiKey = env('NOUPIA_API_KEY', 'CdeCvHo5faqh9v.qhxSsnxcyDp34COlz1zyKfd5FbrWb55_m4gO9qXcP8NMlgLXvB59ZLoVJXyaE.o2mwktRyjxnsZgkRP053lz2sMtf3fRB.R7qy3mISQA8OGbCuZwy');
        $productKey = env('NOUPIA_PRODUCT_KEY', '1SaY2s9Z.C8WKOZcXhGfehz3K4pQ4f06YyHVaKL7pW0GEGoMKWPkzQQySQ.LZqqA0ABUEtAX2ciXkczLXnhTNqpaeusQ5nI0ySawgqZx1tGqxi1lB2khtnN.7hwDzL1L');
        $signature = 'np-' . env('NOUPIA_MODE', 'test'); // 'test' ou 'live'

        $postData = [
            'operation' => 'verify',
            'transaction' => $transactionId,
        ];
        $headers = [
            'Accept: */*',
            'Content-Type: application/json',
            'Noupia-API-Signature: ' . $signature,
            'Noupia-API-Key: ' . $apiKey,
            'Noupia-Product-Key: ' . $productKey,
        ];
        
        // Journalisation de la requête de vérification
        \Log::info('Vérification du paiement Noupia', [
            'transaction_id' => $transactionId,
            'reference' => $reference,
            'user_id' => $user->id,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => $headers,
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return back()->with('error', 'Erreur lors de la vérification du paiement : ' . $error);
        }

        $result = json_decode($response);
        if (!$result || $result->response !== 'success' || ($result->data->status ?? null) !== 'successful') {
            $message = $result->message ?? 'Paiement non validé ou en attente.';
            return back()->with('error', $message);
        }

        // Récupérer les données d'abonnement depuis la session
        $abonnementData = session('abonnement_data');
        
        if (!$abonnementData) {
            \Log::error('Données d\'abonnement manquantes dans la session', [
                'user_id' => $user->id,
                'transaction_id' => $transactionId
            ]);
            return redirect()->route('patient.abonnements.index')
                ->with('error', 'Erreur lors de la validation de l\'abonnement. Veuillez contacter le support.');
        }

        // Créer l'abonnement
        $now = now();
        $dateFin = $now->copy()->addMonth(); // Abonnement d'1 mois
        
        try {
            // Création de l'abonnement
            $abonnement = \App\Models\Abonnement::create([
                'user_id' => $user->id,
                'medecin_id' => $abonnementData['medecin_id'],
                'type' => $abonnementData['pack'],
                'reference' => $abonnementData['reference'],
                'montant' => $abonnementData['montant'],
                'date_debut' => $now,
                'date_fin' => $dateFin,
                'statut' => 'actif',
                'transaction_id' => $transactionId,
            ]);

            // Création de la facture
            $facture = \App\Models\Facture::create([
                'user_id' => $user->id,
                'reference' => $abonnementData['reference'],
                'montant' => $abonnementData['montant'],
                'transaction_id' => $transactionId,
                'date' => $now,
                'details' => 'Paiement abonnement ' . $abonnementData['pack'] . ' via Noupia Pay',
            ]);

            // Mettre à jour l'utilisateur avec le nouveau statut d'abonnement
            $user->abonnement_actif = true;
            $user->save();

            // Nettoyer la session
            session()->forget([
                'noupia_transaction_id', 
                'noupia_reference', 
                'noupia_amount',
                'abonnement_data'
            ]);

            // Envoyer une notification à l'utilisateur
            $user->notify(new \App\Notifications\AbonnementActive($abonnement));
            
            // Envoyer une notification au médecin référent
            $medecin = \App\Models\User::find($abonnementData['medecin_id']);
            if ($medecin) {
                $medecin->notify(new \App\Notifications\NouvelAbonne($abonnement, $user));
            }

            return redirect()->route('patient.abonnements.index')
                ->with('success', 'Votre abonnement a été activé avec succès ! Vous pouvez maintenant bénéficier de tous les avantages de votre pack ' . $abonnementData['pack'] . '.');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de l\'abonnement', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('patient.abonnements.index')
                ->with('error', 'Une erreur est survenue lors de l\'activation de votre abonnement. Notre équipe a été notifiée.');
        }
    }
}
