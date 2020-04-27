<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Post;

class SearchPostController extends Controller
{
    public function search(Request $request)
    {
        $posts = Post::search('*' . request('search') . '*')
            ->with('category')
            ->orderBy('id', 'desc');

        if ($request->has('categories')) {
            $posts->whereIn('category_id', $request->input('categories'));
        }

        return PostResource::collection($posts->paginate());
    }
}
