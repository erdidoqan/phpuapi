<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class PostCategory extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];
    protected $with = ['media'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
