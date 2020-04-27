<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $guarded = [];
    protected $dates = ['start_date', 'end_date'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function scopePaid($query)
    {
        return $query->where('paid', 1);
    }

    public function scopeCurrent($query)
    {
        return $query->paid()
            ->where('start_date', '<=', today())
            ->where('end_date', '>=', today());
    }
}
