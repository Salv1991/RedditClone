<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    protected $postService;

    public function __construct(PostService $postService){
        $this->postService = $postService;
    }

    public function show(User $user, Post $post) {
        $votedCommentsByUser = [];
        $authUser = Auth::user();

        $post->loadCount(['likes', 'dislikes']);
        $comments = $post->comments()->with('user')->withCount(['likes', 'dislikes'])->latest()->get();

        if($authUser) {
            $vote = DB::table('like_posts')
                ->where('user_id', $authUser->id)
                ->where('post_id', $post->id)
                ->first();

            if($comments->isNotEmpty()) {
                $votedCommentsByUser = $this->postService->getVotedComments($authUser, $comments, $post);
            }
        }

        $voteOnPostByUser = $vote ? $vote->type : null;

        return Inertia::render('Posts/Show', [
            'post' => $this->postService->formatPostData($post, $voteOnPostByUser),
            'comments' => $comments->map(function($comment) use ($votedCommentsByUser, $post) {
                $comment->setRelation('post', $post);
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
            : collect();

        $postsLikedByUser = [];

        if($user && $posts->isNotEmpty()){
            $postsLikedByUser = DB::table('like_posts')
                ->where('user_id', $user->id)
                ->pluck('type', 'post_id')
                ->toArray();
        }

        return Inertia::render('Posts/Index', [
            'posts' => $posts->map(function($post) use ($postsLikedByUser) {
                $voteType = $postsLikedByUser[$post->id] ?? null;

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

    public function comment(Post $post, Request $request) {
        $validatedData = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        $this->postService->commentOnPost($post, $validatedData);

        return redirect()->back();
    }

}
