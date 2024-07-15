<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class DeleteCommand
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(int $id): void
    {
        $this->categoryRepository->delete($id);

        $this->redisCacheService->delete(config('redis_keys.categories'));
    }
}
