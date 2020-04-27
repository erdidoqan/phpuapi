<?php

namespace App\Http\Controllers\Api;

use App\SubscriptionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Coupon;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'card_holder_name' => 'required',
            'card_number' => 'required|digits:16',
            'expire_month' => 'required|digits:2',
            'expire_year' => 'required|digits:2',
            'cvc' => 'required|digits:3',
            'subscription_type_id' => 'required'
        ]);

        $coupon = null;
        if ($request->coupon) {
            $coupon = Coupon::available()->where('code', $request->coupon)->first();
            if (!$coupon) {
                return response()->json(['errors' => ['coupon' => ['Geçersiz kupon.']]], 422);
            }
        }

        $subscriptionType = $this->subscriptionTypePrice($request->subscription_type_id, $coupon);

        $result = auth()->user()->subscribe($subscriptionType, $validatedData);
        if (!$result['success']) {
            return response()->json(['errors' => ['card' => [$result['message']]]], 422);
        }

        if ($coupon) {
            $coupon->users()->attach(auth()->user());
        }
    }

    public function update(Request $request)
    {
        if (!auth()->user()->card) {
            return response()->json(['errors' => ['card' => ['Kredi kartınızı güncelleyin.']]], 422);
        }

        $coupon = null;
        if ($request->coupon) {
            $coupon = Coupon::available()->where('code', $request->coupon)->first();
            if (!$coupon) {
                return response()->json(['errors' => ['coupon' => ['Geçersiz kupon.']]], 422);
            }
        }

        $subscriptionType = $this->subscriptionTypePrice($request->subscription_type_id, $coupon);

        $result = auth()->user()->subscribe($subscriptionType);
        if (!$result['success']) {
            return response()->json(['errors' => ['card' => [$result['message']]]], 422);
        }

        if ($coupon) {
            $coupon->users()->attach(auth()->user());
        }
    }

    public function subscriptionTypePrice($subscriptionTypeId, $coupon)
    {
        $subscriptionType = SubscriptionType::findOrFail($subscriptionTypeId);
        if ($coupon) {
            $price = $subscriptionType->price - $coupon->amount;
            $subscriptionType->price = $price >= 0 ? $price : 0;
        }

        return $subscriptionType;
    }

    public function destroy()
    {
        auth()->user()->unsubscribe();
    }
}
