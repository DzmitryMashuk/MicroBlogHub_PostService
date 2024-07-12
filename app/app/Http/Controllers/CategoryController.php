<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->categoryService->getAll());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = $this->categoryService->create($data);

        return response()->json($category, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->categoryService->getById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = $this->categoryService->update($id, $data);

        return response()->json($category);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);

        return response()->json(null, 204);
    }
}
