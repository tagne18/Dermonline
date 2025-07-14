<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté et bloqué
        if (Auth::check() && Auth::user()->is_blocked) {
            // Déconnecter l'utilisateur immédiatement
            Auth::logout();
            
            // Invalider la session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Rediriger vers la page de connexion avec un message d'erreur
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été bloqué par l\'administrateur. Veuillez contacter le support pour plus d\'informations.'
            ]);
        }

        return $next($request);
    }
}
 