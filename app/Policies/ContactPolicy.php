<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','editor']);
    }

    // ===> Aqui está a correção: trocar o tipo de Ad para Contact
    public function view(User $user, Contact $contact): bool
    {
        return in_array($user->role, ['admin','editor']);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Contact $contact): bool
    {
        return false;
    }

    public function delete(User $user, Contact $contact): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user,Contact $contact): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user,Contact $contact): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
