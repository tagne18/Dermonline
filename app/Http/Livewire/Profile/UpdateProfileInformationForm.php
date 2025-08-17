<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;

    public function mount()
    {
        $this->state = Auth::user()->only([
            'name', 'email', 'city', 'profile_photo_path'
        ]);
        
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();

        try {
            $this->validate([
                'state.name' => ['required', 'string', 'max:255'],
                'state.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
                'state.city' => ['nullable', 'string', 'max:255'],
                'photo' => ['nullable', 'image', 'max:2048'], // Augmente la taille max à 2 Mo
            ]);

            $user->update($this->state);

            if ($this->photo) {
                $disk = config('jetstream.profile_photo_disk', 'public');
                \Log::info('Tentative d\'upload de photo de profil', ['user_id' => $user->id, 'original_name' => $this->photo->getClientOriginalName()]);
                $path = $this->photo->storePublicly('profile-photos', ['disk' => $disk]);
                \Log::info('Photo de profil uploadée', ['user_id' => $user->id, 'path' => $path]);
                $user->forceFill([
                    'profile_photo_path' => $path,
                ])->save();
                // Synchronisation automatique 100% PHP
                File::copyDirectory(
                    storage_path('app/public/profile-photos'),
                    public_path('storage/profile-photos')
                );
                // Recharge l'utilisateur avec la nouvelle image (évite le cache)
                $this->user = $user->fresh();
                $this->emit('profilePhotoUpdated'); // Event pour forcer le rafraîchissement côté front
            }

            $this->emit('saved');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'upload de la photo : ' . $e->getMessage());
        }
    }

    public function deleteProfilePhoto()
    {
        Auth::user()->deleteProfilePhoto();
    }

    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
