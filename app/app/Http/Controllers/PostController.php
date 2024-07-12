<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->postService->getAll());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id'     => 'required|integer',
            'category_id' => 'required|integer',
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:posts,slug',
            'content'     => 'required|string',
        ]);

        $post = $this->postService->create($data);

        return response()->json($post, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->postService->getById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'user_id'     => 'integer',
            'category_id' => 'integer',
            'title'       => 'string|max:255',
            'slug'        => 'string|max:255|unique:posts,slug,' . $id,
            'content'     => 'string',
        ]);

        $post = $this->postService->update($id, $data);

        return response()->json($post);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->postService->delete($id);

        return response()->json(null, 204);
    }
}
