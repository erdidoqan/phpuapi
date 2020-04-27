<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'approved' => $this->when(optional(auth()->user())->isAdmin(), $this->approved),
            'type_url' => $this->whenLoaded('commentable', $this->commentable->client_url),
            'likes_count' => $this->likes_count,
            'votes_count' => $this->votes_count,
            'user' => new UserResource($this->whenLoaded('user')),
            'replies' => CommentResource::collection($this->whenLoaded('approvedReplies')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'parent' => new CommentResource($this->whenLoaded('parent')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
