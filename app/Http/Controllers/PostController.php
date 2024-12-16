<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{
   public function index(){
    $posts = Post::all();
    return inertia()->render('Post/Index', ['posts' => $posts]);
    }

    public function create(){
    return Inertia()->render('Post/Create');
    }   

    public function store(Request $request){
    $post = new Post($request->all());
    $post->save();
    return redirect()->route('posts.index');
    }

    public function destroy(Post $post){
    $post->delete();
    return redirect()->back();
    }

    public function edit(Post $post){
    return Inertia()->render('Post/Create', ['post' => $post, 'isUpdating' => true]);
    }

    public function update(Request $request, Post $post){
  $post->update($request->all());
  return redirect()->route('posts.index');
    }
}
