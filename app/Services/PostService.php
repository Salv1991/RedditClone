<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PostService
{

    public function formatPostData($post, $vote = null) {
        $postAuthor = $post->user;

        return [
            'id' => $post->id,
            'username' => $postAuthor->name,
            'post_author_username' => $postAuthor->name,
            'post_author_slug' => $postAuthor->slug,
            'title' => $post->title,
            'text' => $post->text,
            'created_at' => $post->created_at,
            'total_votes' => $post->likes_count - $post->dislikes_count,
            'total_comments' => $post->commentsCount(),
            'user_vote' => $vote,
        ];
    }

    public function formatCommentData($comment, $vote) {
        $commenter = $comment->user;
        $postAuthor = $comment->post->user;

        return [
            'id' => $comment->id,
            'post_id' => $comment->post->id,
            'commenter_username' => $commenter->name,
            'post_author_username' =>$postAuthor->name,
            'post_author_slug' =>$postAuthor->slug,
            'commenter_slug' => $commenter->slug,
            'post_title' => $comment->post->title,
            'total_votes' => $comment->likes_count - $comment->dislikes_count,
            'user_vote' => $vote,
            'text' => $comment->text,
            'created_at' => $comment->created_at,
        ];
    }

    public function getVotedComments($user, $comments, $post) {
        return DB::table('comment_likes')
                ->where('user_id', $user->id)
                ->whereIn('comment_id', $comments->pluck('id'))
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

    public function commentOnPost($post, $validatedData) {
        $comment = Comment::create([
            'parent_id' => null,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'text' => $validatedData['comment'],
        ]);
    }
}
