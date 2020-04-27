<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Thread;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\CommentResource;

class ThreadCommentController extends Controller
{
    public function index($threadId)
    {
        $thread = Thread::published()->findOrFail($threadId);

        $comments = QueryBuilder::for($thread->comments()->approved()->getQuery())
            ->defaultSort('-votes_count')
            ->allowedIncludes('user', 'approved-replies', 'likes.user', 'parent.user')
            ->paginate();

        return CommentResource::collection($comments);
    }

    public function store(Request $request, $threadId)
    {
        $thread = Thread::published()->findOrFail($threadId);

        $validatedData = $request->validate([
            'parent_id' => 'nullable|exists:comments,id',
            'body' => 'required|string',
        ]);

        $validatedData['user_id'] = auth()->id();
        $comment = $thread->comments()->create($validatedData);
        $comment->notifyParent();

        return new CommentResource($comment);
    }
}
