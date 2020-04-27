<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\PostCategoryResource;
use App\PostCategory;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class PostCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('view post categories');

        $postCategories = QueryBuilder::for(PostCategory::class)
            ->defaultSort('-updated_at')
            ->get();

        return PostCategoryResource::collection($postCategories);
    }

    public function show($id)
    {
        $this->authorize('view post categories');

        $postCategory = PostCategory::findOrFail($id);

        return new PostCategoryResource($postCategory);
    }

    public function store(Request $request)
    {
        $this->authorize('create post categories');

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'body' => 'required',
            'image' => 'nullable|image',
        ]);

        $postCategories = PostCategory::create(Arr::except($validatedData, ['image']));
        if ($request->hasFile('image')) {
            $postCategories->addMedia($request->file('image'))->toMediaCollection('post_category');
        }

        return new PostCategoryResource($postCategories);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update post categories');

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'body' => 'required',
            'image' => 'nullable|image',
        ]);

        $postCategories = PostCategory::findOrFail($id);
        $postCategories->update(Arr::except($validatedData, ['image']));

        if ($request->hasFile('image')) {
            optional($postCategories->getFirstMedia())->delete();
            $postCategories->addMedia($request->file('image'))->toMediaCollection('post_category');
        }

        return new PostCategoryResource($postCategories);
    }

    public function destroy($id)
    {
        $this->authorize('delete post categories');

        $postCategory = PostCategory::findOrFail($id);

        if ($postCategory->posts()->count() > 0) {
            return response()->json(['message' => 'Önce bu kategoriye bağlı yazıları silin.'], 403);
        }

        $postCategory->delete();
    }
}
