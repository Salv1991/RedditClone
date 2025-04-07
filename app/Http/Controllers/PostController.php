<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{

    protected $postService;

    public function __construct(PostService $postService){
        $this->postService = $postService;
    }

    public function show($id) {
        $post = Post::with('comments.user')->find($id);

        if (!$post) {
            return redirect()->route('home')->with('error', 'Post not found.');
        }

        $user = Auth::user();

        $vote = $user->votedPosts()
            ->where('post_id', $post->id)
            ->first();

        $voteType = $vote ? $vote->pivot->type : null;

        return Inertia::render('Posts/Show', [
            'post' => $this->postService->formatPostData($post, $voteType),
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
            ? $user->posts()->loadMissing('post.comments')->get()
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

    public function upvote($id) {
        $post = Post::find($id);

        if(!$post || !Auth::check()) {
            return redirect()->route('home')->with('error', 'Post not found.');
        }

        $this->postService->upvote($post);

        return redirect()->back();
    }

    public function downvote($id) {
        $post = Post::find($id);

        if(!$post || !Auth::check()) {
            return redirect()->route('home')->with('error', 'Post not found.');
        }

        $this->postService->downvote($post);

        return redirect()->back();
    }
}
