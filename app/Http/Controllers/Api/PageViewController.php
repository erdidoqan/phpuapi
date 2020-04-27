<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Thread;

class PageViewController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:post,thread',
            'type_id' => 'required',
        ]);

        $models = ['post' => Post::class, 'thread' => Thread::class];
        $model = $models[$validatedData['type']];
        $model::findOrFail($validatedData['type_id'])->increment('views_count');
    }
}
