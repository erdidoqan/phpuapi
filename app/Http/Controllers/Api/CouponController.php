<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Coupon;
use App\Http\Resources\CouponResource;

class CouponController extends Controller
{
    public function show($code)
    {
        $coupon = Coupon::available()->where('code', $code)->firstOrFail();
        
        return new CouponResource($coupon);
    }
}
