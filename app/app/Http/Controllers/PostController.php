<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Redis::get(config('redis_keys.posts'));

        if (!$posts) {
            $posts = Post::all();
            Redis::set(config('redis_keys.posts'), $posts->toJson(), 3600);
        } else {
            $posts = json_decode($posts, true);
        }

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id'     => 'required|integer',
            'category_id' => 'required|integer',
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:posts,slug',
            'content'     => 'required|string',
        ]);

        $post = Post::create($validated);

        Redis::del(config('redis_keys.posts'));

        return response()->json($post, 201);
    }

    public function show(Post $post): JsonResponse
    {
        return response()->json($post);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'user_id'     => 'integer',
            'category_id' => 'integer',
            'title'       => 'string|max:255',
            'slug'        => 'string|max:255|unique:posts,slug,' . $post->id,
            'content'     => 'string',
        ]);

        $post->update($validated);

        Redis::del(config('redis_keys.posts'));

        return response()->json($post);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        Redis::del(config('redis_keys.posts'));

        return response()->json(['message' => 'Success removed'], 204);
    }
}
