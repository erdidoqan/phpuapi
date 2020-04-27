<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SubscriptionTypeResource;
use App\SubscriptionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionTypeController extends Controller
{
    public function index()
    {
        return SubscriptionTypeResource::collection(SubscriptionType::all());
    }

    public function show($id)
    {
        $subscriptionType = SubscriptionType::findOrFail($id);

        return new SubscriptionTypeResource($subscriptionType);
    }
}
