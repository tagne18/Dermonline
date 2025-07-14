@component('mail::message')
# Bienvenue Dr. {{ $user->name }}

Votre compte a été approuvé avec succès. Voici vos informations de connexion :

- **Email :** {{ $user->email }}
- **Mot de passe :** {{ $password }}

@component('mail::button', ['url' => route('login')])
Se connecter
@endcomponent

Merci,<br>
L’équipe {{ config('app.name') }}
@endcomponent
