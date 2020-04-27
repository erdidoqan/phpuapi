<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\ThreadCategoryResource;
use App\ThreadCategory;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThreadCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('view thread categories');

        $threadCategories = QueryBuilder::for(ThreadCategory::class)
            ->defaultSort('-updated_at')
            ->get();

        return ThreadCategoryResource::collection($threadCategories);
    }

    public function show($id)
    {
        $this->authorize('view thread categories');

        $threadCategories = ThreadCategory::findOrFail($id);

        return new ThreadCategoryResource($threadCategories);
    }

    public function store(Request $request)
    {
        $this->authorize('create thread categories');

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'body' => 'required',
        ]);

        $threadCategories = ThreadCategory::create($validatedData);

        return new ThreadCategoryResource($threadCategories);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update thread categories');

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'body' => 'required',
        ]);

        $threadCategories = ThreadCategory::findOrFail($id);
        $threadCategories->update($validatedData);

        return new ThreadCategoryResource($threadCategories);
    }

    public function destroy($id)
    {
        $this->authorize('delete thread categories');

        $threadCategory = ThreadCategory::findOrFail($id);

        if ($threadCategory->threads()->count() > 0) {
            return response()->json(['message' => 'Önce bu kategoriye bağlı forumları silin.'], 403);
        }

        $threadCategory->delete();
    }
}
