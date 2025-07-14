<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Affiche le formulaire de paiement
    public function showForm()
    {
        return view('patient.paiement.form');
    }

    // Lance le paiement via Noupia Pay
    public function process(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits_between:8,15',
            'operator' => 'required|in:orange,mtn',
            'amount' => 'required|numeric|min:100',
        ]);

        // Préparation des paramètres pour l'API Noupia Pay
        $apiUrl = 'https://api.noupia.com/pay';
        $developerKey = env('NOUPIA_DEVELOPER_KEY', 'YOUR_NOUPIA_DEVELOPER_KEY_HERE');
        $subscriptionKey = env('NOUPIA_SUBSCRIPTION_KEY', 'YOUR_NOUPIA_PAY_SUBSCRIPTION_KEY_HERE');
        $reference = 'ABONNEMENT_DERMONLINE_' . uniqid();
        $method = 'mobilemoney';
        $country = 'CM';
        $currency = 'XAF';
        $email = Auth::user()->email;
        $name = Auth::user()->name;

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

        $headers = [
            'Accept: */*',
            'Content-Type: application/json',
            'Noupia-API-Signature: np-live',
            'Noupia-API-Key: ' . $developerKey,
            'Noupia-Product-Key: ' . $subscriptionKey,
        ];

        // Appel API (cURL natif)
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
            return back()->with('error', 'Erreur lors de l’initiation du paiement : ' . $error);
        }

        $result = json_decode($response);
        if (!$result || $result->response !== 'success') {
            $message = $result->message ?? 'Erreur inconnue lors du paiement.';
            return back()->with('error', $message);
        }

        // Paiement initié avec succès
        $transactionId = $result->data->transaction ?? null;
        $ussd = $result->data->channel_ussd ?? null;
        $channel = $result->data->channel_name ?? null;

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
        $developerKey = env('NOUPIA_DEVELOPER_KEY', 'YOUR_NOUPIA_DEVELOPER_KEY_HERE');
        $subscriptionKey = env('NOUPIA_SUBSCRIPTION_KEY', 'YOUR_NOUPIA_PAY_SUBSCRIPTION_KEY_HERE');

        $postData = [
            'operation' => 'verify',
            'transaction' => $transactionId,
        ];
        $headers = [
            'Accept: */*',
            'Content-Type: application/json',
            'Noupia-API-Signature: np-live',
            'Noupia-API-Key: ' . $developerKey,
            'Noupia-Product-Key: ' . $subscriptionKey,
        ];

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

        // Paiement validé : créer l'abonnement, la facture et le rendez-vous
        $now = now();
        $dateFin = $now->copy()->addMonth(); // Abonnement d'1 mois
        $abonnement = \App\Models\Abonnement::create([
            'user_id' => $user->id,
            'type' => 'standard',
            'date_debut' => $now,
            'date_fin' => $dateFin,
            'statut' => 'actif',
        ]);

        // Générer une facture (simple sauvegarde en base ou fichier PDF)
        \App\Models\Facture::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'montant' => $amount,
            'transaction_id' => $transactionId,
            'date' => $now,
            'details' => 'Paiement abonnement via Noupia Pay',
        ]);

        // Enregistrer le rendez-vous si besoin (à adapter selon ton workflow)
        $appointmentData = session('pending_appointment');
        if ($appointmentData) {
            $appointmentData['user_id'] = $user->id;
            $appointmentData['statut'] = 'en_attente';
            \App\Models\Appointment::create($appointmentData);
            session()->forget('pending_appointment');
        }

        // Nettoyer la session
        session()->forget(['noupia_transaction_id', 'noupia_reference', 'noupia_amount']);

        return redirect()->route('patient.appointments.index')->with('success', 'Paiement validé, abonnement activé et facture générée. Vous pouvez maintenant prendre rendez-vous.');
    }
}
