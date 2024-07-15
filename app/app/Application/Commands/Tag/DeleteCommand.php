<?php

declare(strict_types=1);

namespace App\Application\Commands\Tag;

use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class DeleteCommand
{
    public function __construct(
        private TagRepositoryInterface $tagRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(int $id): void
    {
        $this->tagRepository->delete($id);

        $this->redisCacheService->delete(config('redis_keys.tags'));
    }
}
