<?php

namespace App\Policies;

use App\Models\Planning;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanningPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir le planning.
     */
    public function view(User $user, Planning $planning): bool
    {
        return $user->id === $planning->medecin_id;
    }

    /**
     * Détermine si l'utilisateur peut créer des plannings.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('medecin');
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour le planning.
     */
    public function update(User $user, Planning $planning): bool
    {
        return $user->id === $planning->medecin_id && $planning->statut === 'planifie';
    }

    /**
     * Détermine si l'utilisateur peut supprimer le planning.
     */
    public function delete(User $user, Planning $planning): bool
    {
        return $user->id === $planning->medecin_id && $planning->statut === 'planifie';
    }
}
