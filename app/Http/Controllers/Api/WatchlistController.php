<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lesson;

class WatchlistController extends Controller
{
    public function store(Request $request)
    {
        $lesson = Lesson::findOrFail($request->input('lesson_id'));
        auth()->user()->watchlists()->attach($lesson);
    }

    public function destroy($lessonId)
    {
        auth()->user()->watchlists()->detach($lessonId);
    }
}
