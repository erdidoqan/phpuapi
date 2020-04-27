<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DifficultyResource;
use App\Difficulty;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DifficultyController extends Controller
{
    public function index()
    {
        $difficulties = QueryBuilder::for(Difficulty::class)->get();

        return DifficultyResource::collection($difficulties);
    }
}
