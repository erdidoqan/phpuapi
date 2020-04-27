<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use App\Episode;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:comment,episode',
            'type_id' => 'required',
            'vote_type' => 'required|in:up,down',
        ]);

        $models = ['comment' => Comment::class, 'episode' => Episode::class];
        $model = $models[$validatedData['type']];
        $model = $model::findOrFail($validatedData['type_id']);

        $model->votes()->where('user_id', auth()->id())->delete();
        $model->votes()->create([
            'user_id' => auth()->id(),
            'vote_type' => $validatedData['vote_type']
        ]);
    }
}
