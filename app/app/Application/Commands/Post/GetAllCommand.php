<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Application\DTOs\Post\PostListDTO;
use App\Domain\Repositories\PostRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class GetAllCommand
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(): PostListDTO
    {
        $posts = $this->redisCacheService->get(config('redis_keys.posts'));

        if ($posts) {
            return PostListDTO::fromArray(json_decode($posts, true));
        }

        $posts = $this->postRepository->getAll();

        $this->redisCacheService->set(config('redis_keys.posts'), json_encode($posts));

        return new PostListDTO($posts);
    }
}
