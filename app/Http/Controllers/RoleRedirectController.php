<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RoleRedirectController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'medecin') {
            return redirect()->route('medecin.dashboard');
        } else {
            return redirect()->route('profile.show');
        }
    }
}
