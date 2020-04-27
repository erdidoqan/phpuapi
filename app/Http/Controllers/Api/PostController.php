<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Post;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function index()
    {
        $posts = QueryBuilder::for(Post::class)
            ->allowedFilters(Filter::exact('slug'))
            ->allowedIncludes('user', 'category', 'likes.user')
            ->published()
            ->paginate();

        return PostResource::collection($posts);
    }
}
