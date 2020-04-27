<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\ThreadResource;
use App\Thread;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThreadController extends Controller
{
    public function index()
    {
        $this->authorize('view threads');

        $threads = QueryBuilder::for(Thread::class)
            ->defaultSort('-updated_at')
            ->get();

        return ThreadResource::collection($threads);
    }

    public function show($id)
    {
        $this->authorize('view threads');

        $post = Thread::with('category')->findOrFail($id);

        return new ThreadResource($post);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update threads');

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'body' => 'required',
            'published' => 'required|in:0,1',
            'category_id' => 'required|exists:thread_categories,id',
        ]);

        $thread = Thread::findOrFail($id);
        $thread->update($validatedData);

        return new ThreadResource($thread);
    }

    public function destroy($id)
    {
        $this->authorize('delete threads');

        Thread::findOrFail($id)->delete();
    }
}
