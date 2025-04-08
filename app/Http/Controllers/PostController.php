<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Services\PostService;

class PostController extends Controller
{

    protected $postService;

    public function __construct(PostService $postService){
        $this->postService = $postService;
    }

    public function show(Post $post) {
        $post->load(['comments.user']);
        $user = Auth::user()->load(['votedPosts','votedComments']);

        $vote = $user->votedPosts()->firstWhere('post_id', $post->id);
        $voteType = $vote ? $vote->pivot->type : null;

        $comments = $post->comments()->get();
        $votedCommentsByUser = [];
        if($user && $comments->isNotEmpty()) {
            $votedCommentsByUser = $this->postService->getVotedComments($user, $comments, $post);
        }

        return Inertia::render('Posts/Show', [
            'post' => $this->postService->formatPostData($post, $voteType),
            'comments' => $comments->map(function($comment) use ($votedCommentsByUser) {
                $vote = $votedCommentsByUser[$comment->id] ?? null;

                return $this->postService->formatCommentData($comment, $vote);
            }),
        ]);
    }

    public function popular() {
        return Inertia::render('Posts/Index', [
            'posts' => [],
            'title' => 'Popular POSTS',
            'postType' => 'popular',
        ]);
    }

    public function userPosts() {
        $user = Auth::user();

        $posts = Auth::check()
            ? $user->posts()->get()
            : [];

        $userLikes = [];

        if($user && $posts->isNotEmpty()){
            $userLikes =$user->votedPosts()
                ->whereIn('post_id', $posts->pluck('id'))
                ->get(['post_id', 'type'])
                ->keyBy('post_id')
                ->map(function($vote) {
                    return $vote->type;
                })
                ->toArray();
        }

        return Inertia::render('Posts/Index', [
            'posts' => $posts->map(function($post) use ($userLikes) {
                $voteType = $userLikes[$post->id] ?? null;

                return $this->postService->formatPostData($post, $voteType);
            }),
            'postType' => 'default',
        ]);
    }

    public function upvotePost(Post $post) {
        $this->postService->votePost($post, 'like');

        return redirect()->back();
    }

    public function downvotePost(Post $post) {
        $this->postService->votePost($post, 'dislike');

        return redirect()->back();
    }

    public function upvoteComment(Comment $comment) {
        $this->postService->voteComment($comment, 'like');

        return redirect()->back();
    }

    public function downvoteComment(Comment $comment) {
        $this->postService->voteComment($comment, 'dislike');

        return redirect()->back();
    }
}
