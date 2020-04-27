<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;

class Student extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];
    protected $with = ['media'];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(60);
    }

    public function getImageAttribute()
    {
        $url = $this->getFirstMediaUrl('default', 'thumb');

        return $url ? url($url) : asset('/img/logo.png');
    }
}
