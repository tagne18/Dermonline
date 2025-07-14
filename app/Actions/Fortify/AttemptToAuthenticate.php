<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class AttemptToAuthenticate
{
    public function handle(Request $request, $next)
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        // Si l'utilisateur existe mais est bloqué, afficher un message d'erreur spécifique
        if ($user && $user->is_blocked) {
            throw ValidationException::withMessages([
                Fortify::username() => 'Votre compte a été bloqué par l\'administrateur. Veuillez contacter le support pour plus d\'informations.',
            ]);
        }

        // Si l'utilisateur existe et n'est pas bloqué, vérifier le mot de passe
        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }
} 