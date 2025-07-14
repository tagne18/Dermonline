<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CheckBlockedUserOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        // Vérifier si l'utilisateur est bloqué
        if ($user->is_blocked) {
            // Déconnecter immédiatement l'utilisateur
            Auth::logout();
            
            // Invalider la session
            Session::invalidate();
            Session::regenerateToken();
            
            // Stocker le message d'erreur dans la session
            Session::flash('error', 'Votre compte a été bloqué par l\'administrateur. Veuillez contacter le support pour plus d\'informations.');
        }
    }
}
