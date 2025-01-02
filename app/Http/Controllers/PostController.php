<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = PostResource::collection(
            Post::orderByDesc('id')->cursorPaginate(100)
        );
        
        return Inertia::render('Posts/Index', [
            'posts' => $posts
        ]);
    }
}
