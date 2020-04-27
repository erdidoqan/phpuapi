<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'image' => $this->image,
            'image_thumb' => $this->image_thumb,
            'body' => $this->body,
            'excerpt' => $this->excerpt,
            'published' => $this->published,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->approved_comments_count,
            'views_count' => $this->views_count,
            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new PostCategoryResource($this->whenLoaded('category')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
