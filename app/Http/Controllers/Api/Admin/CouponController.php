<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Coupon;
use Spatie\QueryBuilder\QueryBuilder;

class CouponController extends Controller
{
    public function index()
    {
        $this->authorize('view coupons');

        $coupons = QueryBuilder::for(Coupon::class)
            ->defaultSort('-updated_at')
            ->paginate();

        return CouponResource::collection($coupons);
    }

    public function show($id)
    {
        $this->authorize('view coupons');

        $coupon = Coupon::findOrFail($id);

        return new CouponResource($coupon);
    }

    public function store(Request $request)
    {
        $this->authorize('create coupons');

        $validatedData = $request->validate([
            'code' => 'required|unique:coupons',
            'amount' => 'required|numeric',
            'expire_date' => 'required|date|after_or_equal:today',
        ]);

        $coupon = Coupon::create($validatedData);

        return new CouponResource($coupon);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update coupons');

        $coupon = Coupon::findOrFail($id);

        $validatedData = $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'amount' => 'required|numeric',
            'expire_date' => 'required|date|after_or_equal:today',
        ]);

        $coupon->update($validatedData);

        return new CouponResource($coupon);
    }

    public function destroy($id)
    {
        $this->authorize('delete coupons');

        Coupon::findOrFail($id)->delete();
    }
}
