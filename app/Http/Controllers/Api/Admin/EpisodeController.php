<?php

namespace App\Http\Controllers\Api\Admin;

use App\Episode;
use App\Http\Resources\EpisodeResource;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Lesson;
use Illuminate\Support\Arr;

class EpisodeController extends Controller
{
    public function index()
    {
        $this->authorize('view episodes');

        $episodes = QueryBuilder::for(Episode::class);

        if (!auth()->user()->isAdmin()) {
            $episodes = QueryBuilder::for(auth()->user()->episodes()->getQuery());
        }

        $episodes = $episodes->defaultSort('-updated_at')
            ->allowedFilters(Filter::scope('search_text'), Filter::exact('slug'))
            ->with('lesson')
            ->paginate();

        return EpisodeResource::collection($episodes);
    }

    public function show($id)
    {
        $episode = Episode::with('lesson')->findOrFail($id);

        $this->authorize('view', $episode);

        return new EpisodeResource($episode);
    }

    public function store(Request $request)
    {
        $this->authorize('create episodes');

        $lesson = Lesson::findOrNew($request->lesson_id);

        $validatedData = $request->validate($this->createValidation($lesson));

        $episode = $lesson->episodes()->create($validatedData);

        return new EpisodeResource($episode);
    }

    public function update(Request $request, $id)
    {
        $episode = Episode::findOrFail($id);

        $this->authorize('update', $episode);

        $lesson = Lesson::findOrNew($request->lesson_id);

        $validatedData = $request->validate($this->updateValidation($lesson, $episode));

        if ($request->published == 0 && $episode->lesson->isPublished() && $episode->lesson->episodes()->count() < 2) {
            return response()->json(['message' => 'Önce bu bölümün dersini yayından kaldırın.'], 403);
        }

        if ($request->notify == 1 && ($request->published == 0 || !$lesson->isPublished())) {
            return response()->json(['errors' => ['notify' => ['Bildirim göndermek için yayınla alanı seçilmelidir ve ders yayınlanmış olmalıdır.']]], 422);
        }

        $episode->update(Arr::except($validatedData, ['notify']));

        if ($request->notify == 1 && $lesson->isPublished()) {
            $episode->notifyPublished();
        }

        return new EpisodeResource($episode);
    }

    public function destroy($id)
    {
        $episode = Episode::findOrFail($id);

        $this->authorize('delete', $episode);

        if ($episode->lesson->isPublished() && $episode->lesson->episodes()->count() < 2) {
            return response()->json(['message' => 'Önce bu bölümün dersini yayından kaldırın.'], 403);
        }

        $episode->delete();
    }

    private function createValidation($lesson)
    {
        if ($lesson->isStandalone()){
            return [
                'lesson_id' => 'exists:lessons,id',
                'video_id' => '',
                'duration' => 'required',
                'free' => 'required|in:0,1',
                'downloadable' => 'required|in:0,1'
            ];
        }

        return [
            'lesson_id' => 'exists:lessons,id',
            'name' => 'required',
            'slug' => 'required|unique:episodes',
            'description' => 'required',
            'video_id' => '',
            'duration' => 'required',
            'free' => 'required|in:0,1',
            'downloadable' => 'required|in:0,1',
            'order' => 'required|integer',
        ];
    }

    private function updateValidation($lesson, $episode)
    {
        if ($lesson->isStandalone()){
            return [
                'lesson_id' => 'exists:lessons,id',
                'video_id' => 'required_if:published,1',
                'duration' => 'required',
                'free' => 'required|in:0,1',
                'downloadable' => 'required|in:0,1',
                'published' => 'required|in:0,1',
                'notify' => 'required|in:0,1',
            ];
        }

        return [
            'lesson_id' => 'exists:lessons,id',
            'name' => 'required',
            'slug' => 'required|unique:episodes,slug,' . $episode->id,
            'description' => 'required',
            'video_id' => 'required_if:published,1',
            'duration' => 'required',
            'free' => 'required|in:0,1',
            'downloadable' => 'required|in:0,1',
            'published' => 'required|in:0,1',
            'notify' => 'required|in:0,1',
            'order' => 'required|integer',
        ];
    }
}
