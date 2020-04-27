<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Thread;
use App\Http\Resources\ThreadResource;
use Spatie\QueryBuilder\Filter;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = QueryBuilder::for(Thread::class)
            ->allowedFilters(Filter::exact('slug'))
            ->allowedIncludes('user', 'category', 'likes.user')
            ->published()
            ->paginate();

        return ThreadResource::collection($threads);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'category_id' => 'required|exists:thread_categories,id'
        ]);

        $validatedData['user_id'] = auth()->id();
        $validatedData['slug'] = \Str::slug($validatedData['title'], '-');
        $thread = Thread::create($validatedData);
        $thread->searchable();

        return new ThreadResource($thread);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'best_comment_id' => 'required|exists:comments,id'
        ]);

        $thread = auth()->user()->threads()->findOrFail($id);
        $thread->update($validatedData);

        return new ThreadResource($thread);
    }
}
