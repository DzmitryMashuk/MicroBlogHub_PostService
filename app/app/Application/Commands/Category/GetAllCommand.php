<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Application\DTOs\Category\CategoryListDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class GetAllCommand
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(): CategoryListDTO
    {
        $categories = $this->redisCacheService->get(config('redis_keys.categories'));

        if ($categories) {
            return CategoryListDTO::fromArray(json_decode($categories, true));
        }

        $categories = $this->categoryRepository->getAll();

        $this->redisCacheService->set(config('redis_keys.categories'), json_encode($categories));

        return new CategoryListDTO($categories);
    }
}
