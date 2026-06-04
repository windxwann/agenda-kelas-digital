<?php

namespace App\Policies;

use App\Models\User;

class SiswaPolicy
{
    public function view(User $user, $model): bool
    {
        return $user->hasRole('siswa');
    }
}
