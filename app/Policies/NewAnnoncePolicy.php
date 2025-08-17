<?php

namespace App\Policies;

use App\Models\NewAnnonce;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewAnnoncePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /**
     * Détermine si l'utilisateur peut voir n'importe quel modèle.
     */
    public function viewAny(User $user): bool
    {
        // Seuls les médecins peuvent voir leurs propres annonces
        return $user->hasRole('medecin');
    }

    /**
     * Determine whether the user can view the model.
     */
    /**
     * Détermine si l'utilisateur peut voir le modèle.
     */
    public function view(User $user, NewAnnonce $newAnnonce): bool
    {
        // Un médecin ne peut voir que ses propres annonces
        return $user->id === $newAnnonce->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    /**
     * Détermine si l'utilisateur peut créer des modèles.
     */
    public function create(User $user): bool
    {
        // Seuls les médecins peuvent créer des annonces
        return $user->hasRole('medecin');
    }

    /**
     * Determine whether the user can update the model.
     */
    /**
     * Détermine si l'utilisateur peut mettre à jour le modèle.
     */
    public function update(User $user, NewAnnonce $newAnnonce): bool
    {
        // Un médecin ne peut mettre à jour que ses propres annonces
        return $user->id === $newAnnonce->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    /**
     * Détermine si l'utilisateur peut supprimer le modèle.
     */
    public function delete(User $user, NewAnnonce $newAnnonce): bool
    {
        // Un médecin ne peut supprimer que ses propres annonces
        return $user->id === $newAnnonce->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NewAnnonce $newAnnonce): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NewAnnonce $newAnnonce): bool
    {
        return false;
    }
}
