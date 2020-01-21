<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPositionPolicy
{
    use HandlesAuthorization;

   protected $module = '002';

    public function viewAny(User $user)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'V-' . $this->module)->count()
            || in_array($user->userType->id, [1, 2]);
    }

    public function view(User $user, ClientPosition $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'V-' . $this->module)->count()
            || in_array($user->userType->id, [1, 2]);
    }

    public function create(User $user)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'S-' . $this->module)->count()
            || in_array($user->userType->id, [1, 2]);
    }

    public function update(User $user, ClientPosition $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'U-' . $this->module)->count()
            || in_array($user->userType->id, [1, 2]);
    }

    public function delete(User $user, ClientPosition $model)
    {
        $actions = $user->userType->moduleActions;

        return $actions->where('code', 'D-' . $this->module)->count()
            || in_array($user->userType->id, [1, 2]);
    }
}