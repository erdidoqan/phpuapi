<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ThreadResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'description' => Str::words(strip_tags($this->body), 15),
            'published' => $this->published,
            'best_comment_id' => $this->best_comment_id,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->approved_comments_count,
            'views_count' => $this->views_count,
            'category' => new ThreadCategoryResource($this->whenLoaded('category')),
            'user' => new UserResource($this->whenLoaded('user')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
