<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/posts",
     *     summary="Get list of posts",
     *     tags={"Posts"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="ID of the post"),
     *                 @OA\Property(property="user_id", type="integer", description="ID of the user"),
     *                 @OA\Property(property="category_id", type="integer", description="ID of the category"),
     *                 @OA\Property(property="title", type="string", description="Title of the post"),
     *                 @OA\Property(property="slug", type="string", description="Slug of the post"),
     *                 @OA\Property(property="content", type="string", description="Content of the post"),
     *                 @OA\Property(property="created_at", type="string", description="Creation timestamp"),
     *                 @OA\Property(property="updated_at", type="string", description="Last update timestamp")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->postService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "category_id", "title", "slug", "content"},
     *             @OA\Property(property="user_id", type="integer", description="ID of the user"),
     *             @OA\Property(property="category_id", type="integer", description="ID of the category"),
     *             @OA\Property(property="title", type="string", description="Title of the post"),
     *             @OA\Property(property="slug", type="string", description="Slug of the post"),
     *             @OA\Property(property="content", type="string", description="Content of the post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the post"),
     *             @OA\Property(property="user_id", type="integer", description="ID of the user"),
     *             @OA\Property(property="category_id", type="integer", description="ID of the category"),
     *             @OA\Property(property="title", type="string", description="Title of the post"),
     *             @OA\Property(property="slug", type="string", description="Slug of the post"),
     *             @OA\Property(property="content", type="string", description="Content of the post"),
     *             @OA\Property(property="created_at", type="string", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", description="Last update timestamp")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/posts/{id}",
     *     summary="Get a post by ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the post"),
     *             @OA\Property(property="user_id", type="integer", description="ID of the user"),
     *             @OA\Property(property="category_id", type="integer", description="ID of the category"),
     *             @OA\Property(property="title", type="string", description="Title of the post"),
     *             @OA\Property(property="slug", type="string", description="Slug of the post"),
     *             @OA\Property(property="content", type="string", description="Content of the post"),
     *             @OA\Property(property="created_at", type="string", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", description="Last update timestamp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->postService->getById($id));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{id}",
     *     summary="Update a post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", description="ID of the user"),
     *             @OA\Property(property="category_id", type="integer", description="ID of the category"),
     *             @OA\Property(property="title", type="string", description="Title of the post"),
     *             @OA\Property(property="slug", type="string", description="Slug of the post"),
     *             @OA\Property(property="content", type="string", description="Content of the post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the post"),
     *             @OA\Property(property="user_id", type="integer", description="ID of the user"),
     *             @OA\Property(property="category_id", type="integer", description="ID of the category"),
     *             @OA\Property(property="title", type="string", description="Title of the post"),
     *             @OA\Property(property="slug", type="string", description="Slug of the post"),
     *             @OA\Property(property="content", type="string", description="Content of the post"),
     *             @OA\Property(property="created_at", type="string", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", description="Last update timestamp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/{id}",
     *     summary="Delete a post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->postService->delete($id);

        return response()->json(null, 204);
    }
}
