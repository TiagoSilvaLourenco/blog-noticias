<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
     use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $authUser): bool
    {
        return in_array($authUser->role, ['admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->role === 'admin'
            || ($authUser->role === 'editor' && $authUser->id === $user->id);
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser): bool
    {
        return $authUser->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
     public function update(User $authUser, User $user): bool
    {
        // Admin pode editar qualquer, editor só seu próprio perfil
        if ($authUser->role === 'admin') {
            return true;
        }

        if ($authUser->role === 'editor') {
            return $authUser->id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, User $user): bool
    {
        // ninguém pode excluir
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
