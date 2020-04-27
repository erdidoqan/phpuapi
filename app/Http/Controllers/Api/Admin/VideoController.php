<?php

namespace App\Http\Controllers\Api\Admin;

use App\Episode;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create videos');

        $validatedData = $request->validate([
            'episode_id' => 'required',
            'size' => 'required'
        ]);

        $episode = Episode::findOrFail($validatedData['episode_id']);
        $name = $episode->lesson->isStandalone() ? $episode->lesson->name : $episode->name;

        $headers = [
            'Authorization' => 'bearer '.env('VIMEO_TOKEN'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/vnd.vimeo.*+json;version=3.4'
        ];

        $client = new Client([
            'base_uri' => 'https://api.vimeo.com',
            'headers' => $headers
        ]);

        $response = $client->request('POST', '/me/videos', [
            'json' => [
                'upload' => [
                    'approach' => 'tus',
                    'size' => $validatedData['size']
                ],
                'privacy' => [
                    'embed' => 'whitelist',
                    'view' => 'disable',
                    'download' => false
                ],
                'name' => $name
            ]
        ]);
        $json = json_decode($response->getBody(), true);

        $client->request('PUT',$json['uri'] . '/privacy/domains/' . 'phpuzem.com');

        $episode->update([
            'video_id' => str_replace('/videos/','', $json['uri'])
        ]);

        return response()->json([
            'upload_link' => $json['upload']['upload_link']
        ]);
    }

    public function update($episodeId)
    {
        $episode = Episode::findOrFail($episodeId);

        $headers = [
            'Authorization' => 'bearer '.env('VIMEO_TOKEN'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/vnd.vimeo.*+json;version=3.4'
        ];

        $client = new Client([
            'base_uri' => 'https://api.vimeo.com',
            'headers' => $headers
        ]);

        $response = $client->request('GET', '/videos/'.$episode->video_id);
        $json = json_decode($response->getBody(), true);

        $episode->update([
            'duration' => $json['duration']
        ]);
    }
}
