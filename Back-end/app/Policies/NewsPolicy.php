<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('SuperAdmin');
    }

    public function update(User $user, News $news): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('SuperAdmin');
    }

    public function delete(User $user, News $news): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('SuperAdmin');
    }
}
