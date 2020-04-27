<?php

namespace App\Policies;

use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;
    
    public function view(User $user, Post $post)
    {
        if ($user->can('view posts') && $user->id == $post->user_id) {
            return true;
        }
    }

    public function update(User $user, Post $post)
    {
        if ($user->can('update posts') && $user->id == $post->user_id) {
            return true;
        }
    }

    public function delete(User $user, Post $post)
    {
        if ($user->can('delete posts') && $user->id == $post->user_id) {
            return true;
        }
    }
}
