<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Get list of categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="ID of the category"),
     *                 @OA\Property(property="name", type="string", description="Name of the category"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->categoryService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Name of the category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the category"),
     *             @OA\Property(property="name", type="string", description="Name of the category"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = $this->categoryService->create($data);

        return response()->json($category, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     summary="Get a category by ID",
     *     tags={"Categories"},
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
     *             @OA\Property(property="id", type="integer", description="ID of the category"),
     *             @OA\Property(property="name", type="string", description="Name of the category"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->categoryService->getById($id));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     summary="Update a category",
     *     tags={"Categories"},
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
     *             @OA\Property(property="name", type="string", description="Name of the category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the category"),
     *             @OA\Property(property="name", type="string", description="Name of the category"),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = $this->categoryService->update($id, $data);

        return response()->json($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);

        return response()->json(null, 204);
    }
}
