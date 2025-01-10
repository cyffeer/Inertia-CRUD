<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Activity;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Protect actions to ensure user is authenticated
    }

    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        return Inertia::render('Post/Index', [
            'csrf_token' => csrf_token(),
            'meta_title' => 'Posts',
        ]);
    }

    public function getAuthenticatedUser()
    {
        return response()->json(Auth::user());
    }

    public function fetchPosts(Request $request)
    {
        $perPage = (int) $request->input('per_page', 5);
        $query = $request->input('q', '');

        $postsQuery = Post::with('user')
            ->where('title', 'like', "{$query}%")
            ->orWhere('body', 'like', "{$query}%");

        $posts = $postsQuery->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return Inertia::render('Post/Create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'quantity' => 'required|integer',
            'published_at' => 'nullable|date',
        ]);

        // New post 
        $post = new Post($request->all());
        $post->user_id = Auth::id(); 
        $post->save();

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'post_id' => $post->id,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        return Inertia::render('Post/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'quantity' => 'required|integer',
            'published_at' => 'nullable|date',
        ]);

        $post->update($request->all());

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'updated',
            'post_id' => $post->id,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'post_id' => $post->id,
        ]);

        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**public function show()
    {
        $posts = Post::with('user')->get()->toArray(); // Convert the collection to an array
        
        return Inertia::render('Post/Show', [
            'posts' => $posts,
            'authUser' => Auth::user(),
        ]);
    }
        */
}