<?php

namespace App\Policies;

use App\Models\Box;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BoxPolicy
{

    public function validate(User $user, Box $box)
    {
        return $user->hasRole('controller') && !$box->isValidated();
    }

    public function update(User $user, Box $box)
    {
        if (!$box->isValidated()) {
            return $user->hasRole('admin') || $box->user_id == $user->id;
        }

        return $box->isValidated() && $user->hasRole('admin');
    }

    public function show(User $user, Box $box)
    {
        return ($user->hasRole('admin') || $user->hasRole('controller')|| $box->user_id == $user->id);
    }
}
