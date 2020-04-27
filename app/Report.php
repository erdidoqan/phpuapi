<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeAttribute()
    {
        $types = ['App\Comment' => 'comments', 'App\Thread' => 'threads'];

        return $types[$this->reportable_type];
    }
}
