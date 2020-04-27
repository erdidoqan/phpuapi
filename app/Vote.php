<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];

    protected static function boot() {
        static::saved(function ($model) {
            if ($model->votable instanceof Comment) {
                $model->votable->update(['votes_count' => $model->votable->votesCount()]);
            }
        });

        parent::boot();
    }

    public function votable()
    {
        return $this->morphTo();
    }
}
