<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function updateRole(User $currentUser): bool
    {
        return $currentUser->role === 'superadmin';
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        // Superadmin bisa edit semua
        if ($currentUser->role === 'superadmin') {
            return true;
        }

        // Admin hanya bisa edit user biasa, atau dirinya sendiri
        if ($currentUser->role === 'admin') {
            return $targetUser->role === 'user'|| $currentUser->id === $targetUser->id;
        }

        // User biasa hanya bisa edit dirinya sendiri
        return $currentUser->id === $targetUser->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser, User $targetUser): bool
    {
        // Superadmin bisa hapus semua kecuali diri sendiri
        if ($currentUser->role === 'superadmin') {
            return $targetUser->id !== $currentUser->id;
        }

        // Admin hanya bisa hapus user biasa
        if ($currentUser->role === 'admin') {
            return $targetUser->role === 'user';
        }

        // User biasa tidak bisa hapus siapa pun
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
