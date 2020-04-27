<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'standalone' => $this->standalone,
            'published' => $this->published,
            'episodes_count' => $this->published_episodes_count,
            'duration' => $this->duration,
            'client_url' => $this->client_url,
            'watchlist_users' => $this->whenLoaded('watchlistUsers', $this->watchlistUsers->pluck('id')),
            'notifylist_users' => $this->whenLoaded('notifylistUsers', $this->notifylistUsers->pluck('id')),
            'skill' => new SkillResource($this->whenLoaded('skill')),
            'difficulty' => new DifficultyResource($this->whenLoaded('difficulty')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'episodes' => EpisodeResource::collection($this->whenLoaded('publishedEpisodes')),
            'watched_episodes_user' => EpisodeResource::collection($this->whenLoaded('watchedEpisodesUser')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
