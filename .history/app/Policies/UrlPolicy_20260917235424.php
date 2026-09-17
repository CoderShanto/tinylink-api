<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;

class UrlPolicy
{
    /**
     * Determine whether the user can view the model.
     * User A can only view their own URLs.
     */
    public function view(User $user, Url $url): bool
    {
        return $user->id === $url->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * User A can only delete their own URLs.
     */
    public function delete(User $user, Url $url): bool
    {
        return $user->id === $url->user_id;
    }
}