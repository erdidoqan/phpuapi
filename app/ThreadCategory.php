<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadCategory extends Model
{
    protected $guarded = [];

    public function threads()
    {
        return $this->hasMany(Thread::class, 'category_id');
    }
}
