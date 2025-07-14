<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('medecin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'specialite' => 'nullable|string',
            'ville' => 'nullable|string',
            'a_propos' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->specialite = $request->specialite;
        $user->ville = $request->ville;
        $user->a_propos = $request->a_propos;

        // Gestion de la photo de profil
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile-photos', 'public');
            // Supprimer l'ancienne photo si besoin
            if ($user->profile_photo_path) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $path;
        }

        $user->save();

        return redirect()->route('medecin.profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }
}

