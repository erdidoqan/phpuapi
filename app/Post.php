<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use ScoutElastic\Searchable;
use Spatie\MediaLibrary\Models\Media;

class Post extends Model implements HasMedia
{
    use Searchable, HasMediaTrait;

    protected $guarded = [];
    protected $with = ['media'];
    protected $withCount = ['approvedComments', 'likes'];
    protected $indexConfigurator = PostsIndexConfigurator::class;
    protected $mapping = ['properties' => []];

    protected static function boot() {
        static::deleted(function ($model) {
            $model->comments()->delete();
        });

        parent::boot();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(500);
    }

    public function shouldBeSearchable()
    {
        return $this->isPublished();
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function approvedComments()
    {
        return $this->comments()->approved();
    }

    public function parentComments()
    {
        return $this->comments()->whereNull('parent_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getImageAttribute()
    {
        $url = $this->getFirstMediaUrl();

        return $url ? url($url) : asset('/img/background.jpeg');
    }

    public function getImageThumbAttribute()
    {
        $url = $this->getFirstMediaUrl('default', 'thumb');

        return $url ? url($url) : asset('/img/background.jpeg');
    }

    public function getClientPathAttribute()
    {
        return '/blog/' . $this->slug;
    }

    public function getClientUrlAttribute()
    {
        return env('CLIENT_URL') . $this->client_path;
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    public function isPublished()
    {
        return $this->published == 1;
    }

}
