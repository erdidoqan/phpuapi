<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Subscription;
use App\Http\Resources\SubscriptionResource;

class SubscriptionController extends Controller
{
    public function index()
    {
        $this->authorize('view subscriptions');

        $subscriptions = QueryBuilder::for(Subscription::class)
            ->defaultSort('-updated_at')
            ->allowedIncludes('user', 'subscription-type')
            ->paginate();

        return SubscriptionResource::collection($subscriptions);
    }

    public function show($id)
    {
        $this->authorize('view subscriptions');

        $subscription = Subscription::with('user', 'subscriptionType')->findOrFail($id);

        return new SubscriptionResource($subscription);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update subscriptions');

        $validatedData = $request->validate([
            'next_charge_date' => 'nullable|date|after:today',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->update($validatedData);

        return new SubscriptionResource($subscription);
    }
}

