<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\SkillResource;
use App\Skill;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class SkillController extends Controller
{
    public function index()
    {
        $this->authorize('view skills');

        $skills = QueryBuilder::for(Skill::class)
            ->defaultSort('-updated_at')
            ->get();

        return SkillResource::collection($skills);
    }

    public function show($id)
    {
        $this->authorize('view skills');

        $skill = Skill::findOrFail($id);

        return new SkillResource($skill);
    }

    public function store(Request $request)
    {
        $this->authorize('create skills');

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'icon' => 'required',
            'image' => 'required|image',
        ]);

        $skill = Skill::create(Arr::except($validatedData, ['image']));
        $skill->addMedia($request->file('image'))->toMediaCollection();

        return new SkillResource($skill);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update skills');

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'icon' => 'required',
            'image' => 'nullable|image',
        ]);

        $skill = Skill::findOrFail($id);
        $skill->update(Arr::except($validatedData, ['image']));

        if ($request->hasFile('image')) {
            optional($skill->getFirstMedia())->delete();
            $skill->addMedia($request->file('image'))->toMediaCollection();
        }

        return new SkillResource($skill);
    }

    public function destroy($id)
    {
        $this->authorize('delete skills');

        $skill = Skill::findOrFail($id);

        if ($skill->lessons()->count() > 0) {
            return response()->json(['message' => 'Önce bu kategoriye bağlı dersleri silin.'], 403);
        }

        $skill->delete();
    }
}
