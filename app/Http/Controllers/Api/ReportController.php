<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use App\Thread;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:comment,thread',
            'type_id' => 'required',
            'message' => 'required',
        ]);

        $models = ['comment' => Comment::class, 'thread' => Thread::class];
        $model = $models[$validatedData['type']];
        $model::findOrFail($validatedData['type_id'])->reports()->create([
            'user_id' => auth()->id(),
            'message' => $validatedData['message'],
        ]);
    }
}
