<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class UpdateCommand
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(int $id, array $data): CategoryDTO
    {
        $category = $this->categoryRepository->getById($id);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        $this->categoryRepository->update($category, $data);

        $this->redisCacheService->delete(config('redis_keys.categories'));

        return new CategoryDTO(
            $category->id,
            $category->name,
            $category->createdAt,
            $category->updatedAt
        );
    }
}
