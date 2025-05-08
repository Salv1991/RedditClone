<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

class UserController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService){
        $this->postService = $postService;
    }

    public function submittedPosts(User $user, Request $request) {
        $postsQuery = $user->posts()->with('user')->withCount(['likes', 'dislikes', 'comments']);

        $sortBy = $request->query('sort', 'new');

        if($sortBy === 'new') {
            $postsQuery->latest();
        } else if($sortBy === 'top') {
            $postsQuery->orderByRaw('likes_count + dislikes_count DESC');
        } else if($sortBy === 'hot') {
            $postsQuery->orderBy('comments_count', 'desc');
        }

       $posts = $postsQuery->paginate(config('pagination.user_posts'));

       $postsLikedByUser = [];

        if($user && $posts->isNotEmpty()){
            $postsLikedByUser = DB::table('like_posts')
                ->where('user_id', Auth::id())
                ->pluck('type', 'post_id')
                ->toArray();
        }

        return Inertia::render('Users/Show', [
            'data' => $posts->map(function($post) use ($postsLikedByUser) {
                $voteType = $postsLikedByUser[$post->id] ?? null;

                return $this->postService->formatPostData($post, $voteType);
            }),
            'links' => $posts->linkCollection(),
            'pageType' => 'submitted',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'slug' => $user->slug,
            ],
            'sort' => ucfirst($sortBy),
        ]);
    }

    public function submittedComments(User $user, Request $request) {
        $commentsQuery = $user->comments()->with(['user', 'post.user'])->withCount(['dislikes', 'likes']);

        $sortBy = $request->query('sort', 'new');

        if($sortBy === 'new') {
            $commentsQuery->latest();
        } else if($sortBy === 'top') {
            $commentsQuery->orderByRaw('likes_count + dislikes_count DESC');
        } else if($sortBy === 'hot') {
            $commentsQuery->orderByRaw('likes_count - dislikes_count DESC');
        }

        $comments = $commentsQuery->paginate(config('pagination.user_comments'));
        $commentsLikedByUser = DB::table('comment_likes')
            ->where('user_id', Auth::id())
            ->pluck('type', 'comment_id')
            ->toArray();

        return Inertia::render('Users/Show', [
            'data' => $comments->map(function($comment) use ($commentsLikedByUser) {
                $voteType = $commentsLikedByUser[$comment->id] ?? null;
                return $this->postService->formatCommentData($comment, $voteType);
            }),
            'links' => $comments->linkCollection(),
            'pageType' => 'comments',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'slug' => $user->slug,
            ],
            'sort' => ucfirst($sortBy),
        ]);
    }

    //NOT FINISHED
    public function savedPosts(User $user) {
        return Inertia::render('Users/Show', [
            'data' => 'saved',
            'pageType' => 'saved',
            'sort' => ucfirst('test'),
        ]);
    }

    public function showCreatePost() {
        return Inertia::render('Posts/Create');
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:255'],
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $validatedData['title'],
            'text' => $validatedData['text'],
        ]);

        return redirect('/')->with(['message' => 'Post created succesfully.']);
    }
}
