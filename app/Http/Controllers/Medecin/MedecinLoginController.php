<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedecinLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('medecin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'medecin') {
                return redirect()->intended(route('medecin.dashboard'));
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Vous n\'avez pas accès à l\'interface médecin.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe invalide.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('medecin.login');
    }
}
