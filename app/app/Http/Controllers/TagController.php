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

    public function index(): JsonResponse
    {
        return response()->json($this->tagService->getAll());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $tag = $this->tagService->create($data);

        return response()->json($tag, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->tagService->getById($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $date = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id,
        ]);

        $tag = $this->tagService->update($id, $date);

        return response()->json($tag);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->tagService->delete($id);

        return response()->json(null, 204);
    }
}
