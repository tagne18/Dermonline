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
        return view('patient.abonnements.index', compact('medecins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medecin_id' => 'required|exists:users,id',
            'phone' => 'required|digits_between:8,15',
        ]);

        $user = auth()->user();
        $amount = 100; // Montant fixe pour le pack Premium
        $reference = 'ABONNEMENT_PREMIUM_' . strtoupper(uniqid());

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
        ];

        $curl = curl_init();
        \curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.noupia.com/pay',
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
                'Noupia-API-Signature: np-live',
                'Noupia-API-Key: SKpr.w2CbQ8UXV8keJFtA3Z13.SLeL9LX1CeDZFIuJAk22v88Ox6uL6AAOT9.LcqtWUqfiad8WYquZ72wQvj9AXLwW4hGN6Wt7zZcb3chU9aaol1EZIf.XUQ.fX6psM_80vCGoPbB_lbFGO4VckHA9T1z_tw4HBw80cikPf.Z5VBWa0oDha23DjJT94Glbuio0Rq_o..kB0awziAQaTzw7eds6VFfZKcbXOXFzKp.hdPNWd_b0ypp4IWQTKCwx.',
                'Noupia-Product-Key: CdeCvHo5faqh9v.qhxSsnxcyDp34COlz1zyKfd5FbrWb55_m4gO9qXcP8NMlgLXvB59ZLoVJXyaE.o2mwktRyjxnsZgkRP053lz2sMtf3fRB.R7qy3mISQA8OGbCuZwy'
            ],
        ]);

        $response = \curl_exec($curl);
        $error = \curl_error($curl);
        \curl_close($curl);

        if ($error) {
            return back()->with('error', 'Erreur lors de l’initiation du paiement : ' . $error);
        }

        $result = json_decode($response);
        if (!$result || $result->response !== 'success') {
            return back()->with('error', $result->message ?? 'Erreur inconnue lors du paiement.');
        }

        // Stocker les infos nécessaires en session pour vérification
        session([
            'noupia_transaction_id' => $result->data->transaction ?? null,
            'noupia_reference' => $reference,
            'noupia_amount' => $amount,
            'noupia_medecin_id' => $request->medecin_id,
            'noupia_phone' => $request->phone,
        ]);

        // Rediriger vers la page de vérification du paiement
        return redirect()->route('patient.abonnements.verify')->with('info', "Paiement initié. Veuillez valider sur votre téléphone. Après validation, cliquez sur 'Vérifier le paiement'.");
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
        $transactionId = session('noupia_transaction_id');
        if (!$transactionId) {
            return back()->with('error', 'Aucune transaction à vérifier.');
        }

        // Appel API Noupia pour vérifier le paiement
        $postData = [
            'operation' => 'verify',
            'transaction' => $transactionId,
        ];

        $curl = \curl_init();
        \curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.noupia.com/pay',
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
                'Noupia-API-Signature: np-live',
                'Noupia-API-Key: SKpr.w2CbQ8UXV8keJFtA3Z13.SLeL9LX1CeDZFIuJAk22v88Ox6uL6AAOT9.LcqtWUqfiad8WYquZ72wQvj9AXLwW4hGN6Wt7zZcb3chU9aaol1EZIf.XUQ.fX6psM_80vCGoPbB_lbFGO4VckHA9T1z_tw4HBw80cikPf.Z5VBWa0oDha23DjJT94Glbuio0Rq_o..kB0awziAQaTzw7eds6VFfZKcbXOXFzKp.hdPNWd_b0ypp4IWQTKCwx.',
                'Noupia-Product-Key: CdeCvHo5faqh9v.qhxSsnxcyDp34COlz1zyKfd5FbrWb55_m4gO9qXcP8NMlgLXvB59ZLoVJXyaE.o2mwktRyjxnsZgkRP053lz2sMtf3fRB.R7qy3mISQA8OGbCuZwy'
            ],
        ]);

        $response = \curl_exec($curl);
        $error = \curl_error($curl);
        \curl_close($curl);

        if ($error) {
            return back()->with('error', 'Erreur lors de la vérification du paiement : ' . $error);
        }

        $result = json_decode($response);
        if (!$result || $result->response !== 'success' || !$result->data || $result->data->status !== 'successful') {
            return back()->with('error', $result->message ?? 'Paiement non validé. Veuillez réessayer après avoir validé sur votre téléphone.');
        }

        // Ici, vous pouvez enregistrer l’abonnement dans la base de données si besoin
        $user = auth()->user();
        $medecin_id = session('noupia_medecin_id');
        $now = now();
        $date_fin = $now->copy()->addMonth();
        $type = 'premium';
        // On désactive les anciens abonnements actifs
        \App\Models\Abonnement::where('user_id', $user->id)
            ->where('statut', 'actif')
            ->update(['statut' => 'inactif']);
        // Création du nouvel abonnement
        $abonnement = \App\Models\Abonnement::create([
            'user_id'    => $user->id,
            'medecin_id' => $medecin_id,
            'type'       => $type,
            'date_debut' => $now->toDateString(),
            'date_fin'   => $date_fin->toDateString(),
            'statut'     => 'actif',
        ]);

        // Notification Laravel au patient
        $user->notify(new \App\Notifications\AbonnementActivee($abonnement, true));
        // Notification Laravel au médecin
        if ($medecin_id) {
            $medecin = \App\Models\User::find($medecin_id);
            if ($medecin) {
                $medecin->notify(new \App\Notifications\AbonnementActivee($abonnement, false));
            }
        }

        // Nettoyer la session
        session()->forget(['noupia_transaction_id', 'noupia_reference', 'noupia_amount', 'noupia_medecin_id', 'noupia_phone']);

        return redirect()->route('patient.abonnements.index')->with('success', 'Paiement validé et abonnement activé avec succès !');
    }

}