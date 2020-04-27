<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Skill extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];
    protected $with = ['media'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function getClientPathAttribute()
    {
        return '/kategoriler/' . $this->slug;
    }

    public function getClientUrlAttribute()
    {
        return env('CLIENT_URL') . $this->client_path;
    }

    public function getImageAttribute()
    {
        $url = $this->getFirstMediaUrl();

        return $url ? url($url) : asset('/img/logo.png');
    }
}
