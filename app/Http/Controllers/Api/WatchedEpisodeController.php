<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Episode;

class WatchedEpisodeController extends Controller
{
    public function store(Request $request)
    {
        $episode = Episode::findOrFail($request->input('episode_id'));
        auth()->user()->watchedEpisodes()->attach($episode);
    }

    public function destroy($episodeId)
    {
        auth()->user()->watchedEpisodes()->detach($episodeId);
    }
}
