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
        return !$box->isValidated() && ($user->hasRole('admin') || $box->user_id == $user->id);
    }
}
