<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SubscriptionPayment;
use App\Http\Resources\SubscriptionPaymentResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Subscription;

class SubscriptionPaymentController extends Controller
{
    public function index()
    {
        $this->authorize('view subscription payments');

        $payments = QueryBuilder::for(SubscriptionPayment::class)
            ->defaultSort('-updated_at')
            ->allowedFilters('subscription.user.username')
            ->with('subscription.user', 'subscription.subscriptionType')
            ->paginate();

        return SubscriptionPaymentResource::collection($payments);
    }

    public function show($id)
    {
        $this->authorize('view subscription payments');

        $subscriptionPayment = SubscriptionPayment::findOrFail($id);

        return new SubscriptionPaymentResource($subscriptionPayment);
    }

    public function store(Request $request)
    {
        $this->authorize('create subscription payments');

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_type_id' => 'required|exists:subscription_types,id',
            'price' => 'required|numeric|min:0',
            'paid' => 'required|in:0,1',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $subscription = Subscription::create([
            'user_id' => $validatedData['user_id'],
            'subscription_type_id' => $validatedData['subscription_type_id'],
        ]);

        $subscriptionPayment = $subscription->subscriptionPayments()->create([
            'price' => $validatedData['price'],
            'paid' => $validatedData['paid'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
        ]);

        return new SubscriptionPaymentResource($subscriptionPayment);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update subscription payments');

        $validatedData = $request->validate([
            'paid' => 'required|in:0,1',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $subscriptionPayment = SubscriptionPayment::findOrFail($id);
        $subscriptionPayment->update($validatedData);

        return new SubscriptionPaymentResource($subscriptionPayment);
    }
}
