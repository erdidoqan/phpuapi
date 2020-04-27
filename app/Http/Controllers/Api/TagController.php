<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TagResource;
use App\Tag;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index()
    {
        $tags = QueryBuilder::for(Tag::class)->get();

        return TagResource::collection($tags);
    }

}
