<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Vérifier si l'utilisateur est bloqué
        if (Auth::check() && Auth::user()->is_blocked) {
            // Déconnecter l'utilisateur
            Auth::logout();
            
            // Rediriger avec un message d'erreur
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été bloqué par l\'administrateur. Veuillez contacter le support pour plus d\'informations.'
            ]);
        }

        // Redirection normale selon le rôle
        $user = Auth::user();
        return redirect()->intended($user->redirectToDashboard());
    }
}

