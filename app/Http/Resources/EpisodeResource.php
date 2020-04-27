<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth()->user();
        $currentSubscription = $user && $user->currentSubscription;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'video_id' => $this->when($this->free || $currentSubscription || optional($user)->can('view episodes'), $this->video_id),
            'duration' => $this->duration,
            'order' => $this->order,
            'free' => $this->free,
            'downloadable' => $this->downloadable,
            'published' => $this->published,
            'votes_count' => $this->votesCount(),
            'client_url' => $this->client_url,
            'lesson' => new LessonResource($this->whenLoaded('lesson')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
