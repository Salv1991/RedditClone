<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class PostService
{

    public function formatPostData($post, $vote = null) {

        return [
            'id' => $post->id,
            'username' => $post->user->name,
            'title' => $post->title,
            'text' => $post->text,
            'created_at' => $post->created_at,
            'votesCount' => $post->votesCount(),
            'commentsCount' => $post->commentsCount(),
            'userVote' => $vote,
        ];
    }

    public function formatCommentData($comment, $vote) {

        return [
            'id' => $comment->id,
            'votesCount' => $comment->votesCount(),
            'userVote' => $vote,
            'text' => $comment->text,
            'created_at' => $comment->created_at,
            'username' => $comment->user->name,
        ];
    }

    public function getVotedComments($user, $comments, $post) {
        return $user->votedComments()
            ->where('post_id', $post->id)
            ->whereIn('comment_id', $comments->pluck('id'))
            ->get(['comment_id', 'type'])
            ->pluck('type', 'comment_id')
            ->toArray();
    }

    public function votePost($post, $type) {
        $user = Auth::user();

        $existingVote = $user->votedPosts()
            ->where('post_id', $post->id)
            ->first();

        if(!$existingVote) {
            $user->votedPosts()->attach($post->id, ['type' => $type]);
        } else if($existingVote->pivot->type === $type) {
            $user->votedPosts()->detach($post->id);
        } else {
            $user->votedPosts()->updateExistingPivot($post->id, ['type' => $type]);
        }

        return true;
    }

    public function voteComment($comment, $type) {
        $user = Auth::user();

        $existingVote = $user->votedComments()
            ->where('comment_id', $comment->id)
            ->first();



        if(!$existingVote) {
            $user->votedComments()->attach($comment->id, ['type' => $type]);
        } else if ($existingVote->pivot->type === $type) {
            $user->votedComments()->detach($comment->id);
        } else {
            $user->votedComments()->updateExistingPivot($comment->id, ['type' => $type]);
        }
    }
}
