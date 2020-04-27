<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\LessonResource;
use App\Lesson;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LessonController extends Controller
{
    public function index()
    {
        $this->authorize('view lessons');

        $lessons = QueryBuilder::for(Lesson::class)
            ->defaultSort('-updated_at')
            ->allowedFilters('name', Filter::exact('standalone'), Filter::exact('slug'))
            ->allowedIncludes('skill', 'episodes', 'difficulty', 'tags');

        if (!auth()->user()->isAdmin()) {
            $lessons->where('user_id', auth()->id());
        }

        return LessonResource::collection($lessons->paginate());
    }

    public function show($id)
    {
        $lesson = Lesson::with('skill', 'difficulty', 'tags')->findOrFail($id);

        $this->authorize('view', $lesson);

        return new LessonResource($lesson);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Lesson::class);

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:lessons',
            'description' => 'required',
            'image' => 'required|image|max:2000',
            'standalone' => 'required|in:0,1',
            'skill_id' => 'required|exists:skills,id',
            'difficulty_id' => 'required|exists:difficulties,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validatedData['user_id'] = auth()->id();
        $lesson = Lesson::create(Arr::except($validatedData, ['image', 'tags']));
        $lesson->addMedia($request->file('image'))->toMediaCollection();
        $lesson->tags()->sync($validatedData['tags']);

        return new LessonResource($lesson);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $this->authorize('update', $lesson);

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:lessons,slug,' . $lesson->id,
            'description' => 'required',
            'image' => 'nullable|image|max:2000',
            'standalone' => 'required|in:0,1',
            'published' => 'required|in:0,1',
            'notify' => 'required|in:0,1',
            'skill_id' => 'required|exists:skills,id',
            'difficulty_id' => 'required|exists:difficulties,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($request->notify == 1 && $request->published == 0) {
            return response()->json(['errors' => ['notify' => ['Bildirim göndermek için yayınla alanı seçilmelidir.']]], 422);
        }

        if (!$lesson->isPublished() && $validatedData['published'] == 1 && !$lesson->publishedEpisodes()->exists()) {
            return response()->json([
                'message' => 'Bu dersi yayınlamak için bir bölüm oluşturun veya yayınlayın.'
            ], 403);
        }

        $lesson->update(Arr::except($validatedData, ['image', 'tags', 'notify']));

        if ($request->hasFile('image')) {
            optional($lesson->getFirstMedia())->delete();
            $lesson->addMedia($request->file('image'))->toMediaCollection();
        }
        $lesson->tags()->sync($validatedData['tags']);

        if ($request->notify == 1) {
            $lesson->notifyPublished();
        }

        return new LessonResource($lesson);
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);

        $this->authorize('delete', $lesson);

        if ($lesson->episodes()->count() > 0) {
            return response()->json(['message' => 'Önce bu derse bağlı bölümleri silin.'], 403);
        }

        $lesson->delete();
    }
}
