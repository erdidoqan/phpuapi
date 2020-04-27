<?php

namespace App;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use ScoutElastic\Searchable;
use App\Notifications\LessonPublished;
use Illuminate\Support\Facades\Notification;

class Lesson extends Model implements HasMedia
{
    use Searchable, HasMediaTrait;

    protected $guarded = [];
    protected $with = ['media'];
    protected $indexConfigurator = LessonsIndexConfigurator::class;
    protected $mapping = ['properties' => []];

    protected static function boot() {
        static::deleting(function ($model) {
            $model->tags()->detach();
        });

        parent::boot();
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['skill'] = $this->skill_id;
        $array['difficulty'] = $this->difficulty_id;
        $array['tags'] = $this->tags()->pluck('id');

        return $array;
    }

    public function shouldBeSearchable()
    {
        return $this->isPublished();
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function publishedEpisodes()
    {
        return $this->episodes()->published();
    }

    public function watchedEpisodesUser()
    {
        return $this->episodes()->wherehas('watchedUsers', function ($query) {
            return $query->where('user_id', auth()->id());
        });
    }

    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function watchlistUsers()
    {
        return $this->belongsToMany(User::class, 'watchlists');
    }

    public function notifylistUsers()
    {
        return $this->belongsToMany(User::class, 'notifylists');
    }

    public function getImageAttribute()
    {
        $url = $this->getFirstMediaUrl();

        return $url ? url($url) : asset('/img/logo.png');
    }

    public function getClientPathAttribute()
    {
        return $this->isStandalone() ? '/dersler/' . $this->slug : '/seri-dersler/' . $this->slug;
    }

    public function getClientUrlAttribute()
    {
        return env('CLIENT_URL') . $this->client_path;
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    public function scopeSeries($query)
    {
        return $query->where('standalone', 0);
    }

    public function isPublished()
    {
        return $this->published == 1;
    }

    public function isStandalone()
    {
        return $this->standalone == 1;
    }

    public function notifyPublished()
    {
        foreach (User::all() as $user) {
            $user->notify(new LessonPublished($this));
        }

        foreach (Newsletter::all() as $newsletter) {
            Notification::route('mail', $newsletter->email)->notify(new LessonPublished($this));
        }
    }
}
