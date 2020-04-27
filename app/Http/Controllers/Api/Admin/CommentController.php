<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Comment;
use App\Http\Resources\CommentResource;
use Spatie\QueryBuilder\Filter;

class CommentController extends Controller
{
    public function index()
    {
        $this->authorize('view comments');

        $comments = QueryBuilder::for(Comment::class)
            ->defaultSort('-updated_at')
            ->allowedFilters(Filter::scope('search_text'))
            ->with('user')
            ->paginate();

        return CommentResource::collection($comments);
    }

    public function show($id)
    {
        $this->authorize('view comments');

        $comment = Comment::findOrFail($id);

        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update comments');

        $validatedData = $request->validate([
            'body' => 'required',
            'approved' => 'required|in:0,1'
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($validatedData);

        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $this->authorize('delete comments');

        Comment::findOrFail($id)->delete();
    }
}
