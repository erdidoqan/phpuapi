<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
    protected $guarded = [];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
