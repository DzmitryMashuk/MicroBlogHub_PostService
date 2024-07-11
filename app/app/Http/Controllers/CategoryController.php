<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Redis::get(config('redis_keys.categories'));

        if (!$categories) {
            $categories = Category::all();
            Redis::set(config('redis_keys.categories'), $categories->toJson(), 3600);
        } else {
            $categories = json_decode($categories, true);
        }

        return response()->json($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create($validated);

        Redis::del(config('redis_keys.categories'));

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        Redis::del(config('redis_keys.categories'));

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        Redis::del(config('redis_keys.categories'));

        return response()->json(['message' => 'Success removed'], 204);
    }
}
