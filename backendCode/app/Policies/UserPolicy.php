<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('viewAny User')
        ? Response::allow()
        : Response::deny(__('Permissione denied'), 401);
    }
}
