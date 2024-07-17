<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(private TagService $tagService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags",
     *     summary="Get list of tags",
     *     tags={"Tags"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="ID of the tag"),
     *                 @OA\Property(property="name", type="string", description="Name of the tag"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->tagService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tags",
     *     summary="Create a new tag",
     *     tags={"Tags"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Name of the tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the tag"),
     *             @OA\Property(property="name", type="string", description="Name of the tag"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $tag = $this->tagService->create($data);

        return response()->json($tag, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/{id}",
     *     summary="Get a tag by ID",
     *     tags={"Tags"},
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
     *             @OA\Property(property="id", type="integer", description="ID of the tag"),
     *             @OA\Property(property="name", type="string", description="Name of the tag"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->tagService->getById($id));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tags/{id}",
     *     summary="Update a tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Name of the tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the tag"),
     *             @OA\Property(property="name", type="string", description="Name of the tag"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $date = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id,
        ]);

        $tag = $this->tagService->update($id, $date);

        return response()->json($tag);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tags/{id}",
     *     summary="Delete a tag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tag deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->tagService->delete($id);

        return response()->json(null, 204);
    }
}
