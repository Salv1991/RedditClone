<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use hasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'text',
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likedByUsers() {
        return $this->belongsToMany(User::class, 'like_posts')
            ->withPivot('type')
            ->withTimeStamps();
    }

    public function voters() {
        return $this->belongsToMany(User::class, 'like_posts');
    }

    public function likes() {
        return $this->voters()->wherePivot('type', '=', 'like');
    }

    public function dislikes() {
        return $this->voters()->wherePivot('type', '=', 'dislike');
    }

    // public function votesCount() {
    //     return $this->likesCount()->count() - $this->dislikesCount();
    // }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id')
            ->withCount(['likes', 'dislikes']);
    }

    public function commentsCount() {
        return $this->comments()->count();
    }

}
