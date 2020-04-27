<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'subscription_type_id' => $this->subscription_type_id,
            'next_charge_date' => optional($this->next_charge_date)->format('Y-m-d'),
            'expire_date' => optional($this->expire_date)->format('Y-m-d'),
            'user' => new UserResource($this->whenLoaded('user')),
            'subscription_type' => new SubscriptionTypeResource($this->whenLoaded('subscriptionType'))
        ];
    }
}
