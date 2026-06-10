<?php

namespace App\Policies;

use App\Models\User;

class WakasekPolicy
{
    public function view(User $user, $model = null): bool
    {
        return $user->hasRole('wakasek_kurikulum');
    }
}
