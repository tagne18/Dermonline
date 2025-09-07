<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function index()
    {
        $medecins = \App\Models\User::where('role', 'medecin')
            ->where('is_blocked', false)
            ->orderBy('name')
            ->get();
            
        // Récupérer l'abonnement actif de l'utilisateur s'il existe
        $abonnementActif = auth()->user()->abonnements()
            ->where('statut', 'actif')
            ->first();
            
        return view('patient.abonnements.index', compact('medecins', 'abonnementActif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medecin_id' => 'required|exists:users,id',
            'phone' => 'required|digits_between:8,15',
        ]);

        $user = auth()->user();
        
        // Vérifier si l'utilisateur a déjà un abonnement actif
        $abonnementActif = $user->abonnements()
            ->where('statut', 'actif')
            ->exists();
            
        if ($abonnementActif) {
            return back()->with('error', 'Vous avez déjà un abonnement actif.');
        }

        $amount = 300; // Montant pour le pack Premium en FCFA
        $reference = 'ABONNEMENT_' . strtoupper(uniqid());
        $medecin = \App\Models\User::findOrFail($request->medecin_id);

        try {
            // Préparation appel API Noupia
            $postData = [
                'operation'   => 'initiate',
                'reference'   => $reference,
                'amount'      => $amount,
                'phone'       => $request->phone,
                'method'      => 'mobilemoney',
                'country'     => 'CM',
                'currency'    => 'XAF',
                'email'       => $user->email,
                'name'        => $user->name,
                'description' => 'Abonnement Premium - ' . $medecin->name,
            ];

            // Configuration directe de l'API Noupia
            $apiKey = 'CdeCvHo5faqh9v.qhxSsnxcyDp34COlz1zyKfd5FbrWb55_m4gO9qXcP8NMlgLXvB59ZLoVJXyaE.o2mwktRyjxnsZgkRP053lz2sMtf3fRB.R7qy3mISQA8OGbCuZwy';
            $productKey = '1SaY2s9Z.C8WKOZcXhGfehz3K4pQ4f06YyHVaKL7pW0GEGoMKWPkzQQySQ.LZqqA0ABUEtAX2ciXkczLXnhTNqpaeusQ5nI0ySawgqZx1tGqxi1lB2khtnN.7hwDzL1L';
            $endpoint = 'https://api.noupia.com/pay'; // Endpoint de production
            $signature = 'np-live'; // Signature pour l'environnement de production
            
            $curl = curl_init();
            \curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    'Accept: */*',
                    'Content-Type: application/json',
                    'Noupia-API-Signature: ' . $signature,
                    'Noupia-API-Key: ' . $apiKey,
                    'Noupia-Product-Key: ' . $productKey
                ],
            ]);

            $response = \curl_exec($curl);
            $error = \curl_error($curl);
            $httpCode = \curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlInfo = \curl_getinfo($curl);
            \curl_close($curl);

            // Log de la réponse brute
            \Log::info('Réponse brute API Noupia - Initialisation', [
                'http_code' => $httpCode,
                'error' => $error,
                'response' => $response,
                'curl_info' => $curlInfo
            ]);

            if ($error) {
                throw new \Exception('Erreur lors de la connexion à Noupia: ' . $error);
            }

            $result = json_decode($response);
            
            if (!$result) {
                throw new \Exception('Réponse invalide de l\'API Noupia: ' . $response);
            }

            if ($result->response !== 'success') {
                throw new \Exception('Erreur Noupia: ' . ($result->message ?? 'Erreur inconnue'));
            }

            // Sauvegarder la transaction en base de données
            $transaction = new \App\Models\Transaction([
                'user_id' => $user->id,
                'reference' => $reference,
                'amount' => $amount,
                'status' => 'pending',
                'payment_method' => 'mobilemoney',
                'metadata' => [
                    'medecin_id' => $medecin->id,
                    'medecin_name' => $medecin->name,
                    'phone' => $request->phone,
                    'transaction_id' => $result->data->transaction ?? null
                ]
            ]);
            
            $transaction->save();

            // Stocker les informations de la transaction en session pour la vérification
            session([
                'noupia_transaction_id' => $result->data->transaction ?? null,
                'noupia_reference' => $reference,
                'noupia_amount' => $amount,
                'noupia_medecin_id' => $medecin->id,
                'noupia_phone' => $request->phone
            ]);

            return redirect()->route('patient.abonnements.verify')
                ->with('success', 'Veuillez confirmer le paiement sur votre téléphone.');
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'initiation du paiement Noupia', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de l\'initiation du paiement: ' . $e->getMessage());
        }
    }

    public function verify(Request $request)
    {
        // Afficher la page de vérification avec les infos de session
        $transactionId = session('noupia_transaction_id');
        $reference = session('noupia_reference');
        $amount = session('noupia_amount');
        $medecin_id = session('noupia_medecin_id');
        $phone = session('noupia_phone');
        return view('patient.abonnements.verify', compact('transactionId', 'reference', 'amount', 'medecin_id', 'phone'));
    }

    public function verifyPayment(Request $request)
    {
        // Vérifier si les informations de transaction sont en session
        if (!session()->has('noupia_transaction_id') || !session()->has('noupia_medecin_id')) {
            return redirect()->route('patient.abonnements.index')
                ->with('error', 'Session de paiement invalide. Veuillez réessayer.');
        }

        $transactionId = session('noupia_transaction_id');
        $medecinId = session('noupia_medecin_id');
        $reference = session('noupia_reference');
        $amount = session('noupia_amount');
        $phone = session('noupia_phone');
        $user = auth()->user();

        // Récupérer la transaction en attente
        $transaction = \App\Models\Transaction::where('reference', $reference)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            return redirect()->route('patient.abonnements.index')
                ->with('error', 'Transaction introuvable ou déjà traitée.');
        }

        try {
            // Préparation de la requête de vérification
            $postData = [
                'operation' => 'verify',
                'transaction' => $transactionId,
            ];

            // Configuration directe de l'API Noupia
            $apiKey = 'CdeCvHo5faqh9v.qhxSsnxcyDp34COlz1zyKfd5FbrWb55_m4gO9qXcP8NMlgLXvB59ZLoVJXyaE.o2mwktRyjxnsZgkRP053lz2sMtf3fRB.R7qy3mISQA8OGbCuZwy';
            $productKey = '1SaY2s9Z.C8WKOZcXhGfehz3K4pQ4f06YyHVaKL7pW0GEGoMKWPkzQQySQ.LZqqA0ABUEtAX2ciXkczLXnhTNqpaeusQ5nI0ySawgqZx1tGqxi1lB2khtnN.7hwDzL1L';
            $endpoint = 'https://api.noupia.com/pay'; // Endpoint de production
            $signature = 'np-live'; // Signature pour l'environnement de production
            
            $curl = curl_init();
            \curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    'Accept: */*',
                    'Content-Type: application/json',
                    'Noupia-API-Signature: ' . $signature,
                    'Noupia-API-Key: ' . $apiKey,
                    'Noupia-Product-Key: ' . $productKey
                ],
            ]);

            $response = \curl_exec($curl);
            $error = \curl_error($curl);
            $httpCode = \curl_getinfo($curl, CURLINFO_HTTP_CODE);
            \curl_close($curl);

            if ($error) {
                throw new \Exception('Erreur lors de la connexion à Noupia: ' . $error);
            }

            $result = json_decode($response);
            
            if (!$result) {
                throw new \Exception('Réponse invalide de l\'API Noupia: ' . $response);
            }

            // Log de la réponse de vérification
            \Log::info('Réponse vérification API Noupia', [
                'http_code' => $httpCode,
                'response' => $result,
                'transaction_id' => $transactionId
            ]);

            if ($result->response !== 'success') {
                // Mettre à jour le statut de la transaction
                $transaction->update([
                    'status' => 'failed',
                    'metadata' => array_merge($transaction->metadata, [
                        'error' => $result->message ?? 'Erreur inconnue',
                        'verification_response' => $result
                    ])
                ]);
                
                return redirect()->route('patient.abonnements.index')
                    ->with('error', 'Échec de la vérification du paiement: ' . ($result->message ?? 'Erreur inconnue'));
            }

            // Vérifier si le paiement a été effectué avec succès
            if (isset($result->data->status) && $result->data->status === 'completed') {
                // Mettre à jour le statut de la transaction
                $transaction->update([
                    'status' => 'completed',
                    'transaction_id' => $result->data->transaction_id ?? $transactionId,
                    'metadata' => array_merge($transaction->metadata, [
                        'payment_date' => now()->toDateTimeString(),
                        'verification_response' => $result->data
                    ])
                ]);

                // Récupérer le médecin
                $medecin = \App\Models\User::findOrFail($medecinId);
                
                // Créer l'abonnement
                $abonnement = new \App\Models\Abonnement([
                    'user_id' => $user->id,
                    'medecin_id' => $medecinId,
                    'date_debut' => now(),
                    'date_fin' => now()->addMonth(),
                    'statut' => 'actif',
                    'montant' => $amount,
                    'reference' => $reference,
                    'transaction_id' => $result->data->transaction_id ?? $transactionId
                ]);
                
                $abonnement->save();

                // Lier la transaction à l'abonnement
                $transaction->update([
                    'transactionable_id' => $abonnement->id,
                    'transactionable_type' => \App\Models\Abonnement::class
                ]);

                // Envoyer des notifications
                if (class_exists('App\\Notifications\\AbonnementActivated')) {
                    $user->notify(new \App\Notifications\AbonnementActivated($abonnement));
                }
                
                if (class_exists('App\\Notifications\\NouvelAbonnement')) {
                    $medecin->notify(new \App\Notifications\NouvelAbonnement($abonnement));
                }

                // Nettoyer la session
                session()->forget([
                    'noupia_transaction_id',
                    'noupia_medecin_id',
                    'noupia_reference',
                    'noupia_amount',
                    'noupia_phone'
                ]);

                return redirect()->route('patient.abonnements.index')
                    ->with('success', 'Votre abonnement a été activé avec succès !');
            } else {
                // Le paiement n'est pas encore finalisé
                $transaction->update([
                    'status' => 'pending',
                    'metadata' => array_merge($transaction->metadata, [
                        'verification_response' => $result->data ?? $result
                    ])
                ]);
                
                return redirect()->route('patient.abonnements.verify')
                    ->with('warning', 'Paiement en attente de confirmation. Veuillez réessayer dans quelques instants.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification du paiement Noupia', [
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('patient.abonnements.verify')
                ->with('error', 'Une erreur est survenue lors de la vérification du paiement: ' . $e->getMessage());
        }
    }
}
