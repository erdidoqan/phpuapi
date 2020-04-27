<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\PostResource;
use App\Post;
use Spatie\MediaLibrary\Models\Media;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class PostController extends Controller
{
    public function index()
    {
        $this->authorize('view posts');

        $posts = QueryBuilder::for(Post::class)->defaultSort('-updated_at');

        if (!auth()->user()->isAdmin()) {
            $posts->where('user_id', auth()->id());
        }

        return PostResource::collection($posts->paginate());
    }

    public function show($id)
    {
        $post = Post::with('category')->findOrFail($id);

        $this->authorize('view', $post);

        return new PostResource($post);
    }

    public function store(Request $request)
    {
        $this->authorize('create posts');

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:posts',
            'image' => 'required|image|max:2000',
            'body' => 'required',
            'excerpt' => 'required',
            'published' => 'required|in:0,1',
            'category_id' => 'required|exists:post_categories,id',
        ]);

        $validatedData['user_id'] = auth()->id();
        $posts = Post::create(Arr::except($validatedData, ['image']));
        $posts->addMedia($request->file('image'))->toMediaCollection();

        return new PostResource($posts);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $validatedData = $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'image' => 'nullable|image|max:2000',
            'body' => 'required',
            'excerpt' => 'required',
            'published' => 'required|in:0,1',
            'category_id' => 'required|exists:post_categories,id',
        ]);

        $post->update(Arr::except($validatedData, ['image']));

        if ($request->hasFile('image')) {
            $post->media()->delete();
            $post->addMedia($request->file('image'))->toMediaCollection();
        }

        return new PostResource($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        $post->delete();
    }
}
