<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Support\Facades\Auth;

class AssignPatientRole
{
    public function __construct()
    {
        //
    }

    public function handle(Registered $event)
    {
        $user = $event->user;
        $user->role = 'patient'; // Assigner le rôle directement
        $user->save();

        // Si l'utilisateur n'a pas vérifié son mail, le déconnecter
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
        }
    }
}
