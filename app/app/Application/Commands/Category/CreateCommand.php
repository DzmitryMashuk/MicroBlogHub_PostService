<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class CreateCommand
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(array $data): CategoryDTO
    {
        $category = $this->categoryRepository->create($data);

        $this->redisCacheService->delete(config('redis_keys.categories'));

        return new CategoryDTO(
            $category->id,
            $category->name,
            $category->createdAt,
            $category->updatedAt
        );
    }
}
