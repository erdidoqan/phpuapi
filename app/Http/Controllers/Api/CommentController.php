<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = auth()->user()->comments()->findOrFail($id);
        $comment->update($validatedData);

        return new CommentResource($comment);
    }
}
