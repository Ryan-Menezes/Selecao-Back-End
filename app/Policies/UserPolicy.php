<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    public function view(User $user, User $model)
    {
        return $user->is_admin;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, User $model)
    {
        return $user->is_admin;
    }

    public function delete(User $user, User $model)
    {
        return $user->is_admin;
    }

    public function restore(User $user, User $model)
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, User $model)
    {
        return $user->is_admin;
    }
}
