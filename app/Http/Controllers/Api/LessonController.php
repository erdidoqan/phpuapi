<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LessonResource;
use App\Lesson;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = QueryBuilder::for(Lesson::class)
            ->defaultSort('-updated_at')
            ->allowedFilters(Filter::exact('slug'), Filter::exact('standalone'), Filter::exact('skill_id'))
            ->allowedIncludes('skill', 'published-episodes', 'watched-episodes-user', 'difficulty', 'tags')
            ->published()
            ->with('watchlistUsers', 'notifylistUsers')
            ->withCount('publishedEpisodes')
            ->withCount(['publishedEpisodes as duration' => function ($query) {
                $query->select(DB::raw('SUM(duration)'));
            }])
            ->paginate();

        return LessonResource::collection($lessons);
    }

    public function show($id)
    {
        $lesson = Lesson::with('skill', 'difficulty', 'tags')->published()->findOrFail($id);

        return new LessonResource($lesson);
    }
}
