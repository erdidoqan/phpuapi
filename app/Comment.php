<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\CommentReplied;

class Comment extends Model
{
    protected $guarded = [];
    protected $withCount = ['likes'];

    protected static function boot() {
        static::deleted(function ($model) {
            $model->replies->each->delete();
            $model->reports->each->delete();
        });

        parent::boot();
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function approvedReplies()
    {
        return $this->replies()->approved();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
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

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }

    public function scopeSearchText($query, $text)
    {
        return $query->where('body', 'like', '%' . $text . '%')
            ->orWhereHas('user', function ($query) use ($text) {
                return $query->where('username', 'like', '%' . $text . '%');
            });
    }

    public function votesCount()
    {
        return $this->upVotes()->count() - $this->downVotes()->count();
    }

    public function notifyParent()
    {
        if ($this->parent) {
            $this->parent->user->notify(new CommentReplied($this));
        }
    }
}
