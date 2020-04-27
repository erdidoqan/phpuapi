<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Episode;
use App\Http\Resources\CommentResource;
use Spatie\QueryBuilder\QueryBuilder;

class EpisodeCommentController extends Controller
{
    public function index($episodeId)
    {
        $episode = Episode::published()->findOrFail($episodeId);

        $comments = QueryBuilder::for($episode->parentComments()->approved()->getQuery())
            ->defaultSort('-votes_count')
            ->allowedIncludes('user', 'approved-replies.user', 'likes.user', 'approved-replies.likes.user')
            ->get();

        return CommentResource::collection($comments);
    }

    public function store(Request $request, $episodeId)
    {
        $episode = Episode::published()->findOrFail($episodeId);

        $validatedData = $request->validate([
            'parent_id' => 'nullable|exists:comments,id',
            'body' => 'required|string',
        ]);

        $validatedData['user_id'] = auth()->id();
        $comment = $episode->comments()->create($validatedData);
        $comment->notifyParent();

        return new CommentResource($comment);
    }
}
