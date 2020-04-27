<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('expire_date', '>=', now())->whereDoesntHave('users', function ($query) {
            return $query->where('user_id', auth()->id());
        });
    }
}
