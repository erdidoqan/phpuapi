<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use Searchable;

    protected $guarded = [];
    protected $withCount = ['approvedComments', 'likes'];
    protected $indexConfigurator = ThreadsIndexConfigurator::class;
    protected $mapping = ['properties' => []];

    protected static function boot() {
        static::deleted(function ($model) {
            $model->comments->each->delete();
            $model->reports->each->delete();
        });

        parent::boot();
    }

    public function shouldBeSearchable()
    {
        return $this->isPublished();
    }

    public function category()
    {
        return $this->belongsTo(ThreadCategory::class);
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

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function getClientPathAttribute()
    {
        return '/forum/' . $this->slug;
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
