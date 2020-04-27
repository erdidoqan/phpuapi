<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Comment;
use App\Thread;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:comment,post,thread',
            'type_id' => 'required'
        ]);

        $models = ['comment' => Comment::class, 'post' => Post::class, 'thread' => Thread::class];
        $model = $models[$validatedData['type']];
        $model::findOrFail($validatedData['type_id'])
            ->likes()
            ->firstOrCreate(['user_id' => auth()->id()]);
    }

    public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:comment,post,thread',
            'type_id' => 'required'
        ]);

        $models = ['comment' => Comment::class, 'post' => Post::class, 'thread' => Thread::class];
        $model = $models[$validatedData['type']];
        $model::findOrFail($validatedData['type_id'])
            ->likes()
            ->where('user_id', auth()->id())
            ->delete();
    }
}
