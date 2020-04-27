<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ScoutElastic\Searchable;
use App\Notifications\EpisodePublished;
use Illuminate\Support\Facades\Notification;

class Episode extends Model
{
    use Searchable;

    protected $guarded = [];
    protected $withCount = ['upVotes', 'downVotes'];
    protected $indexConfigurator = EpisodesIndexConfigurator::class;
    protected $mapping = ['properties' => []];

    protected static function boot() {
        static::deleted(function ($model) {
            $model->comments()->delete();
        });

        parent::boot();
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['skill'] = $this->lesson->skill_id;
        $array['difficulty'] = $this->lesson->difficulty_id;
        $array['tags'] = $this->lesson->tags()->pluck('id');

        return $array;
    }

    public function shouldBeSearchable()
    {
        return $this->isPublished() && $this->lesson->isPublished() && !$this->lesson->isStandalone();
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function parentComments()
    {
        return $this->comments()->whereNull('parent_id');
    }

    public function watchedUsers()
    {
        return $this->belongsToMany(User::class, 'watched_episodes');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function upVotes()
    {
        return $this->votes()->where('vote_type', 'up');
    }

    public function downVotes()
    {
        return $this->votes()->where('vote_type', 'down');
    }

    public function getClientPathAttribute()
    {
        return '/seri-dersler/bolumler/' . $this->slug;
    }

    public function getClientUrlAttribute()
    {
        return env('CLIENT_URL') . $this->client_path;
    }

    public function scopeSearchText($query, $text)
    {
        return $query->where('name', 'like', '%'.$text.'%')->orWhereHas('lesson', function ($query) use ($text) {
            return $query->where('name', 'like', '%'.$text.'%');
        });
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    public function isPublished()
    {
        return $this->published == 1;
    }

    public function votesCount()
    {
        return $this->up_votes_count - $this->down_votes_count;
    }

    public function notifyDisablePublished()
    {
        foreach ($this->lesson->notifylistUsers as $user) {
            $user->notify(new EpisodePublished($this));
        }
    }

    public function notifyPublished()
    {
        foreach (User::all() as $user) {
            $user->notify(new EpisodePublished($this));
        }

        foreach (Newsletter::all() as $newsletter) {
            Notification::route('mail', $newsletter->email)->notify(new EpisodePublished($this));
        }
    }
}
