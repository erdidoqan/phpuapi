<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }
}
