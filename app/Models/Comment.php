<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Comment extends Model
{
    protected $fillable = [
        'text',
        'user_id',
        'post_id',
        'likes'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function getVotesCountAttribute(): int
    {
        return $this->votesCount();
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function votedByUsers() {
        return $this->belongsToMany(User::class, 'comment_likes')
            ->withPivot('type')
            ->withTimeStamps();
    }

    public function voters() {
        return $this->belongsToMany(User::class, 'comment_likes');
    }

    public function likes() {
        return $this->voters()->wherePivot('type', '=', 'like');
    }

    public function dislikes() {
        return $this->voters()->wherePivot('type', '=', 'dislike');
    }

    // public function votesCount() {
    //     return $this->likesCount()->count() - $this->dislikesCount()->count();
    // }
}
