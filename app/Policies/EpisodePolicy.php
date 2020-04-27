<?php

namespace App\Policies;

use App\User;
use App\Episode;
use Illuminate\Auth\Access\HandlesAuthorization;

class EpisodePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Episode $episode)
    {
        if ($user->can('view episodes') && $user->id == $episode->lesson->user_id) {
            return true;
        }
    }

    public function update(User $user, Episode $episode)
    {
        if ($user->can('update episodes') && $user->id == $episode->lesson->user_id) {
            return true;
        }
    }

    public function delete(User $user, Episode $episode)
    {
        if ($user->can('delete episodes') && $user->id == $episode->lesson->user_id) {
            return true;
        }
    }
}
