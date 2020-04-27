<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\TagResource;
use App\Tag;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index()
    {
        $this->authorize('view tags');

        $tags = QueryBuilder::for(Tag::class)
            ->defaultSort('-updated_at')
            ->get();

        return TagResource::collection($tags);
    }

    public function show($id)
    {
        $this->authorize('view tags');

        $tags = Tag::findOrFail($id);

        return new TagResource($tags);
    }

    public function store(Request $request)
    {
        $this->authorize('create tags');

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $tags = Tag::create($validatedData);

        return new TagResource($tags);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update tags');

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $tags = Tag::findOrFail($id);
        $tags->update($validatedData);

        return new TagResource($tags);
    }

    public function destroy($id)
    {
        $this->authorize('delete tags');

        $tag = Tag::findOrFail($id);

        if ($tag->lessons()->count() > 0) {
            return response()->json(['message' => 'Önce bu etikete bağlı dersleri silin.'], 403);
        }

        $tag->delete();
    }
}
