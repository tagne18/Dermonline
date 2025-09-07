<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ordonnance Médicale</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; }
        .header p { margin: 5px 0; }
        .patient-info { margin-bottom: 20px; }
        .medicaments { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .medicaments th, .medicaments td { border: 1px solid #000; padding: 8px; text-align: left; }
        .signature { margin-top: 50px; text-align: right; }
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ORDONNANCE MÉDICALE</h1>
        <p>Dr. {{ $data['medecin_nom'] }}</p>
        <p>{{ $data['medecin_specialite'] }}</p>
        <p>{{ $data['medecin_adresse'] }}</p>
        <p>Tél: {{ $data['medecin_telephone'] }}</p>
    </div>

    <div class="patient-info">
        <p><strong>Patient:</strong> {{ $data['patient_nom'] }}</p>
        <p><strong>Date de naissance:</strong> {{ $data['patient_naissance'] }}</p>
        <p><strong>Date de l'ordonnance:</strong> {{ $data['date'] }}</p>
    </div>

    <h3>Médicaments prescrits:</h3>
    <table class="medicaments">
        <thead>
            <tr>
                <th>Médicament</th>
                <th>Posologie</th>
                <th>Durée</th>
                <th>Instructions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['medicaments'] as $medicament)
            <tr>
                <td>{{ $medicament['nom'] }}</td>
                <td>{{ $medicament['posologie'] }}</td>
                <td>{{ $medicament['duree'] }}</td>
                <td>{{ $medicament['instructions'] ?? 'Sans objet' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(!empty($data['commentaires']))
    <div class="commentaires">
        <h3>Commentaires et recommandations:</h3>
        <p>{{ $data['commentaires'] }}</p>
    </div>
    @endif

    <div class="signature">
        <p>Fait à {{ $data['lieu'] }}, le {{ $data['date'] }}</p>
        <br><br>
        <p>Signature et cachet du médecin</p>
    </div>

    <div class="footer">
        <p>Document généré électroniquement par DermOnline - Ne peut servir d'ordonnance sécurisée</p>
    </div>
</body>
</html>
