<?php

namespace App\Http\Controllers\Api;

use App\Episode;
use App\Lesson;
use App\Http\Controllers\Controller;
use App\Post;
use App\Skill;
use App\Thread;

class SitemapController extends Controller
{
    public function index()
    {
        $lessons = Lesson::published()->get();
        $episodes = Episode::published()->whereHas('lesson', function ($query) {
            return $query->series()->published();
        })->get();
        $skills = Skill::all();
        $posts = Post::published()->get();
        $threads = Thread::published()->get();

        $data = $lessons->concat($episodes)->concat($skills)->concat($posts)->concat($threads);

        return response()->json($data->map->client_path);
    }
}
