<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
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
    public function index(Request $request)
{
    
    $userId = $request->input('user_id', '');
    $postsQuery = Post::with('user');

    if ($userId) {
        $postsQuery->whereHas('user', function ($query) use ($userId) {
            $query->where('id', 'like', $userId . '%');
        });
    }

    // Paginate
    $posts = $postsQuery->paginate(5);
    
    return Inertia::render('Post/Index', [
        'posts' => $posts,
        'authUser' => Auth::user(),
    ]);
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
        ]);

        // New post 
        $post = new Post($request->all());
        $post->user_id = Auth::id(); 
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        // Owned post by users to edit 
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', 'You cannot edit this post.');
        }

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
        ]);

        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized');
        }

        $post->update($request->all());

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully');
    }
}

