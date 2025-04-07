<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class PostService
{

    public function formatPostData($post, $vote=null) {
        $comments = $post->comments()->get();
        $user = Auth::user();

        $votedComments = [];

        if($user && $comments->isNotEmpty()) {
            $votedComments = $this->getVotedComments($comments, $post);
        }

        return [
            'id' => $post->id,
            'username' => $post->user->name,
            'title' => $post->title,
            'text' => $post->text,
            'created_at' => $post->created_at,
            'likesCount' => $post->likesCount(),
            'commentsData' => $comments->map(function($comment) use ($votedComments) {
                $vote = $votedComments[$comment->id] ?? null;

                return $this->formatCommentData($comment, $vote);
            }),
            'commentsCount' => $post->commentsCount(),
            'voteByUser' => $vote,
        ];
    }

    public function formatCommentData($comment, $vote) {
        return [
            'id' => $comment->id,
            'votesCount' => $comment->votesCount(),
            'voteByUser' => $vote,
            'text' => $comment->text,
            'created_at' => $comment->created_at,
            'username' => $comment->user->name,
        ];
    }

    public function getVotedComments($comments, $post) {
        return Auth::user()->votedComments()
            ->where('post_id', $post->id)
            ->whereIn('comment_id', $comments->pluck('id'))
            ->get(['comment_id', 'type'])
            ->keyBy('comment_id')
            ->map(function($comment){
                return $comment->type;
            })
            ->toArray();
    }


    public function upvote($post) {
        $user = Auth::user();

        $existingVote = $user->votedPosts()
            ->where('post_id', $post->id)
            ->first();

        if(!$existingVote) {
            $user->votedPosts()->attach($post->id, ['type' => 'like']);
        } else if($existingVote->pivot->type === 'dislike') {
                $user->votedPosts()->updateExistingPivot($post->id, ['type' => 'like']);
        } else {
            $user->votedPosts()->detach($post->id);
        }

        return true;
    }

    public function downvote($post) {
        $user = Auth::user();

        $existingVote = $user->votedPosts()
            ->where('post_id', $post->id)
            ->first();

        if(!$existingVote) {
            $user->votedPosts()->attach($post->id, ['type' => 'dislike']);
        } else if($existingVote->pivot->type === 'like') {
                $user->votedPosts()->updateExistingPivot($post->id, ['type' => 'dislike']);
        } else {
            $user->votedPosts()->detach($post->id);
        }

        return true;
    }

}
