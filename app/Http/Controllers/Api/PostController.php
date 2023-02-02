<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
        $this->middleware('scopes:read-post')->only('index', 'show');
        $this->middleware(['scopes:create-post', 'can:create post'])->only('store');
        $this->middleware(['scopes:update-post', 'can:edit post'])->only('update');
        $this->middleware(['scopes:delete-post', 'can:delete post'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::included()->filter()->sort()->getOrPagination();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|unique:posts',
            'stract' => 'required',
            'body' => 'required',
            'status' => 'required',
            'category_id' => 'required|exists:categories,id', 
        ]);
        
        $user = auth()->user();
        $data['user_id'] = $user->id;


        $posts = Post::create($data);
        return PostResource::make($posts);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $posts = Post::included()->findOrFail($id);
        return PostResource::make($posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('author', $post);
        $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|unique:posts,slug,'. $request->id,
        ]);
        $post->update($request->all());
        return PostResource::make($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('author', $post);
        $post->delete();
        return PostResource::make($post);
    }
}
