<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $permissions = $user ? $user->getAllPermissions() : null;
        $permissions = $permissions ? $permissions->pluck('name') : [];

        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->when($user && ($user->isAdmin() || $user->id == $this->id), $this->name),
            'email' => $this->when($user && ($user->isAdmin() || $user->id == $this->id), $this->email),
            'avatar' => $this->avatar,
            'active' => $this->when($user && $user->isAdmin(), $this->active),
            'comments_count' => $this->comments_count,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'current_subscription' => $this->when($user && ($user->isAdmin() || $user->id == $this->id), new SubscriptionResource($this->whenLoaded('currentSubscription'))),
            'paid_subscriptions' => $this->when($user && ($user->isAdmin() || $user->id == $this->id), SubscriptionResource::collection($this->whenLoaded('paidSubscriptions'))),
            'payments' => $this->when($user && ($user->isAdmin() || $user->id == $this->id), SubscriptionPaymentResource::collection($this->whenLoaded('subscriptionPayments'))),
            'watchlist' => LessonResource::collection($this->whenLoaded('watchlists')),
            'notifications' => NotificationResource::collection($this->whenLoaded('notifications')),
            'watched_episodes' => EpisodeResource::collection($this->whenLoaded('watchedEpisodes')),
            'credit_card' => $this->when($user && ($user->isAdmin() || $user->id == $this->id), $this->whenLoaded('card', optional($this->card)->bin_number)),
            'permissions' => $this->whenLoaded('permissions', $permissions),
            'created_at' => $this->when($user && $user->isAdmin(), $this->created_at),
            'updated_at' => $this->when($user && $user->isAdmin(), $this->updated_at),
        ];
    }
}
