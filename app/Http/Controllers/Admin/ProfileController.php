<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'different:current_password'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Mise à jour des informations de base
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;

        // Mise à jour du mot de passe si fourni
        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        // Gestion de la photo de profil
        if ($request->hasFile('profile_photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Enregistrer la nouvelle photo
            $imagePath = $request->file('profile_photo')->store('profile-photos', 'public');
            
            // Redimensionner l'image
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(500, 500);
            $image->save();

            $user->profile_photo_path = $imagePath;
        }

        $user->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
