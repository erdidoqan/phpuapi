<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ThreadCategoryResource;
use App\ThreadCategory;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;

class ThreadCategoryController extends Controller
{
    public function index()
    {
        $threadCategories = QueryBuilder::for(ThreadCategory::class)->get();

        return ThreadCategoryResource::collection($threadCategories);
    }
}
