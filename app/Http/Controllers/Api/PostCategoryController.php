<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostCategoryResource;
use App\PostCategory;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;

class PostCategoryController extends Controller
{
    public function index()
    {
        $postCategories = QueryBuilder::for(PostCategory::class)->get();

        return PostCategoryResource::collection($postCategories);
    }
}
