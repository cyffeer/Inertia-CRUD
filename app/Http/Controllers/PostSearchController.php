<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Inertia\Inertia;

class PostSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->q;
        $type = $request->type;
        $perPage = (int) $request->input('per_page', session('per_page', 5)); // Default to 5 items per page
        session(['per_page' => $perPage]); // Store per_page in session

        if ($type == 'post') {
            return $this->searchByPost($query, $perPage);
        }
    }

    private function searchByPost($query, $perPage)
    {
        $postsQuery = Post::with('user');

        if ($query) {
            $postsQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', '%' . $query . '%')
                             ->orWhere('body', 'like', '%' . $query . '%');
            });
        }

        $posts = $postsQuery->paginate($perPage);

        return response()->json($posts);
    }

    /**public function show(Request $request, $id)
    {
        $post = Post::with('user')->findOrFail($id);

        return Inertia::render('Post/Show', [
            'post' => $post,
            'csrf_token' => csrf_token(),
        ]);
    }
        */
}