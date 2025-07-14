<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Création de compte</title>
</head>
<body>
    <h1>Bonjour {{ $user->name }}</h1>
    <p>Votre compte médecin a été validé et créé avec succès.</p>
    <p>Vous pouvez maintenant vous connecter avec :</p>
    <ul>
        <li><strong>Email :</strong> {{ $user->email }}</li>
        <li><strong>Mot de passe temporaire :</strong> {{ $password }}</li>
    </ul>
    <p>Veuillez changer votre mot de passe dès votre première connexion.</p>

    <p>Merci,</p>
    <p>L’équipe de gestion</p>
</body>
</html>
