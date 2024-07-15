<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Domain\Repositories\PostRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class DeleteCommand
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(int $id): void
    {
        $this->postRepository->delete($id);

        $this->redisCacheService->delete(config('redis_keys.posts'));
    }
}
