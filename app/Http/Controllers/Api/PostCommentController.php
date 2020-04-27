<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Post;
use App\Http\Resources\CommentResource;

class PostCommentController extends Controller
{
    public function index($postId)
    {
        $post = Post::published()->findOrFail($postId);

        $comments = QueryBuilder::for($post->parentComments()->approved()->getQuery())
            ->defaultSort('-votes_count')
            ->allowedIncludes('user', 'approved-replies.user', 'likes.user', 'approved-replies.likes.user')
            ->get();

        return CommentResource::collection($comments);
    }

    public function store(Request $request, $postId)
    {
        $post = Post::published()->findOrFail($postId);

        $validatedData = $request->validate([
            'parent_id' => 'nullable|exists:comments,id',
            'body' => 'required|string',
        ]);

        $validatedData['user_id'] = auth()->id();
        $comment = $post->comments()->create($validatedData);
        $comment->notifyParent();

        return new CommentResource($comment);
    }
}
