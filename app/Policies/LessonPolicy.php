<?php

namespace App\Policies;

use App\User;
use App\Lesson;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Lesson $lesson)
    {
        if ($user->can('view lessons') && $user->id == $lesson->user_id) {
            return true;
        }
    }

    public function update(User $user, Lesson $lesson)
    {
        if ($user->can('update lessons') && $user->id == $lesson->user_id) {
            return true;
        }
    }

    public function delete(User $user, Lesson $lesson)
    {
        if ($user->can('delete lessons') && $user->id == $lesson->user_id) {
            return true;
        }
    }
}
