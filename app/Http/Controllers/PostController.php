<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(10);
    
        return Inertia::render('Posts/Index', [
            'posts' => PostResource::collection($posts),
        ]); 
    }
    
}
