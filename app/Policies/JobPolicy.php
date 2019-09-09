<?php

namespace App\Policies;

use App\Models\User;

class JobPolicy
{
    public function list(User $user) {
        return true;
    }

}