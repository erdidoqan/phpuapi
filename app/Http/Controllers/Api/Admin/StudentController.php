<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\DifficultyResource;
use App\Difficulty;
use App\Http\Resources\StudentResource;
use App\Student;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class StudentController extends Controller
{
    public function index()
    {
        $this->authorize('view students');

        $students = QueryBuilder::for(Student::class)
            ->defaultSort('-updated_at')
            ->get();

        return StudentResource::collection($students);
    }

    public function show($id)
    {
        $this->authorize('view students');

        $student = Student::findOrFail($id);

        return new StudentResource($student);
    }

    public function store(Request $request)
    {
        $this->authorize('create students');

        $validatedData = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'email' => 'required|email',
            'twitter_url' => 'required|url',
            'image' => 'required|image|max:2000',
            'description' => ''
        ]);

        $student = Student::create(Arr::except($validatedData, ['image']));
        $student->addMedia($request->file('image'))->toMediaCollection();

        return new StudentResource($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $this->authorize('update students');

        $validatedData = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'email' => 'required|email',
            'twitter_url' => 'required|url',
            'image' => 'nullable|image|max:2000',
            'description' => ''
        ]);

        $student->update(Arr::except($validatedData, ['image']));

        if ($request->hasFile('image')) {
            optional($student->getFirstMedia())->delete();
            $student->addMedia($request->file('image'))->toMediaCollection();
        }

        return new StudentResource($student);
    }

    public function destroy($id)
    {
        $this->authorize('delete students');

        Student::findOrFail($id)->delete();
    }
}
