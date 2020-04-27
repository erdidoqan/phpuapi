<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    protected $guarded = [];

    public function endDate($startDate)
    {
        return $startDate->copy()->addMonths($this->months);
    }
}
