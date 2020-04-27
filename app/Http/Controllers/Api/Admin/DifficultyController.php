<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\DifficultyResource;
use App\Difficulty;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DifficultyController extends Controller
{
    public function index()
    {
        $this->authorize('view difficulties');

        $difficulties = QueryBuilder::for(Difficulty::class)
            ->defaultSort('-updated_at')
            ->get();

        return DifficultyResource::collection($difficulties);
    }

    public function show($id)
    {
        $this->authorize('view difficulties');

        $difficulty = Difficulty::findOrFail($id);

        return new DifficultyResource($difficulty);
    }

    public function store(Request $request)
    {
        $this->authorize('create difficulties');

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $difficulty = Difficulty::create($validatedData);

        return new DifficultyResource($difficulty);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update difficulties');

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $difficulty = Difficulty::findOrFail($id);
        $difficulty->update($validatedData);

        return new DifficultyResource($difficulty);
    }

    public function destroy($id)
    {
        $this->authorize('delete difficulties');

        $difficulty = Difficulty::findOrFail($id);

        if ($difficulty->lessons()->count() > 0) {
            return response()->json(['message' => 'Önce bu zorluğa bağlı dersleri silin.'], 403);
        }

        $difficulty->delete();
    }
}
