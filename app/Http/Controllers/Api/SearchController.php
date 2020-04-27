<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\Http\Resources\LessonResource;
use App\Http\Resources\EpisodeResource;
use App\Episode;

class SearchController extends Controller
{
    public function search()
    {
        $lessonType = request('lesson_type');
        $filters = request()->all('skill', 'difficulty', 'tags');

        if ($lessonType == 'episode') {
            $results = $this->filter($filters)->orderBy('id', 'desc');
            return EpisodeResource::collection($results->paginate())
                ->additional(['meta' => ['facets' => ['values' => $this->facets($filters), 'lesson_type' => $lessonType]]]);
        }

        if ($lessonType != 'all') {
            $filters['standalone'] = $lessonType == 'standalone' ? 1 : 0;
        }
        $results = $this->filter($filters)->orderBy('id', 'desc');

        return LessonResource::collection($results->paginate())
            ->additional(['meta' => ['facets' => ['values' => $this->facets($filters), 'lesson_type' => $lessonType]]]);
    }

    private function query()
    {
        if (request('lesson_type') == 'episode') {
            return Episode::search('*' . request('search') . '*')->with('lesson.skill');
        }

        $query = Lesson::search('*' . request('search') . '*')->with('skill');

        return request('lesson_type') == 'all'
            ? $query
            : $query->where('standalone', request('lesson_type') == 'standalone' ? 1 : 0);
    }

    private function filter($filters)
    {
        $query = $this->query();
        if (!empty($filters['skill'])) {
            $query->whereIn('skill', \Arr::wrap($filters['skill']));
        }
        if (!empty($filters['difficulty'])) {
            $query->whereIn('difficulty', \Arr::wrap($filters['difficulty']));
        }
        if (!empty($filters['tags'])) {
            $query->whereIn('tags', \Arr::wrap($filters['tags']));
        }

        return $query;
    }

    private function facets($filters)
    {
        $facets = [];
        foreach ($filters as $filterExcept => $value) {
            $facets[$filterExcept] = $this->facet($filters, $filterExcept);
        }

        return $facets;
    }

    private function facet($filters, $filterExcept)
    {
        $filters = \Arr::except($filters, $filterExcept);
        $query = $this->filter($filters);

        $queryPayload = $query->buildPayload()->first();
        $queryPayload['body']['size'] = 0;
        $queryPayload['body']['aggs'] = [
            $filterExcept => ['terms' => ['field' => $filterExcept]],
        ];

        $aggs = $query->model::searchRaw($queryPayload['body'])['aggregations'];

        return \Arr::pluck($aggs[$filterExcept]['buckets'], 'doc_count', 'key');
    }
}
