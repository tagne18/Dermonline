<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('medecin.profile.show', compact('user'));
    }
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
            'langue' => 'nullable|string',
            'lieu_travail' => 'nullable|string',
            'numero_licence' => 'nullable|string',
            'matricule_professionnel' => 'nullable|string',
            'experience_professionnelle' => 'nullable|string',
            'domaine_expertise' => 'nullable|string',
            'a_propos' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'old_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->specialite = $request->specialite;
        $user->ville = $request->ville;
        $user->langue = $request->langue;
        $user->lieu_travail = $request->lieu_travail;
        $user->numero_licence = $request->numero_licence;
        $user->matricule_professionnel = $request->matricule_professionnel;
        $user->experience_professionnelle = $request->experience_professionnelle;
        $user->domaine_expertise = $request->domaine_expertise;
        $user->a_propos = $request->a_propos;
        $user->gender = $request->gender;
        $user->birth_date = $request->birth_date;

        // Gestion de la photo de profil
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile-photos', 'public');
            if ($user->profile_photo_path) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $path;
        }

        // Gestion du mot de passe
        if ($request->filled('password')) {
            if (!\Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'L’ancien mot de passe est incorrect.']);
            }
            $user->password = \Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('medecin.profile.show')->with('success', 'Profil mis à jour avec succès.');
    }
}

