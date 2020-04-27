<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\SubscriptionTypeResource;
use App\SubscriptionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionTypeController extends Controller
{
    public function index()
    {
        $this->authorize('view subscription types');

        return SubscriptionTypeResource::collection(SubscriptionType::all());
    }

    public function show($id)
    {
        $subscriptionType = SubscriptionType::findOrFail($id);

        return new SubscriptionTypeResource($subscriptionType);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update subscription types');

        $validatedData = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'required|string',
            'icon' => 'required|string',
            'price' => 'required|numeric|min:0',
            'months' => 'nullable|integer|min:1',
        ]);

        $subscriptionType = SubscriptionType::findOrFail($id);
        $subscriptionType->update($validatedData);

        return new SubscriptionTypeResource($subscriptionType);
    }
}
