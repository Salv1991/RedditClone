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

    public function likesCount() {
        return $this->likedByUsers()->count();
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function commentsCount() {
        return $this->comments()->count();
    }

}
