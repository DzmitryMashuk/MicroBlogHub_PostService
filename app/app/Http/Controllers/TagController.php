<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tags = Redis::get(config('redis_keys.tags'));

        if (!$tags) {
            $tags = Tag::all();
            Redis::set(config('redis_keys.tags'), $tags->toJson(), 3600);
        } else {
            $tags = json_decode($tags, true);
        }

        return response()->json($tags);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $tag = Tag::create($validated);

        Redis::del(config('redis_keys.tags'));

        return response()->json($tag, 201);
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json($tag);
    }

    public function update(Request $request, Tag $tag): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $tag->update($validated);

        Redis::del(config('redis_keys.tags'));

        return response()->json($tag);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        Redis::del(config('redis_keys.tags'));

        return response()->json(['message' => 'Success removed'], 204);
    }
}
