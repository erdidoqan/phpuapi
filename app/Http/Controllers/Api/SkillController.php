<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SkillResource;
use App\Skill;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\Filter;

class SkillController extends Controller
{
    public function index()
    {
        $skills = QueryBuilder::for(Skill::class)
            ->allowedFilters(Filter::exact('slug'))
            ->get();

        return SkillResource::collection($skills);
    }
}
