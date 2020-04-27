<?php

namespace App\Http\Controllers\Api;

use App\Episode;
use App\Http\Resources\EpisodeResource;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index()
    {
        $episodes = QueryBuilder::for(Episode::class)
            ->allowedFilters(Filter::exact('slug'))
            ->allowedIncludes('lesson.skill', 'lesson.published-episodes', 'lesson.watched-episodes-user',  'lesson.difficulty', 'lesson.tags')
            ->published()
            ->paginate();

        return EpisodeResource::collection($episodes);
    }

    public function show($id)
    {
        $episode = Episode::with('lesson')->published()->findOrFail($id);

        return new EpisodeResource($episode);
    }
}
