<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Application\DTOs\Post\PostDTO;
use App\Domain\Repositories\PostRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class UpdateCommand
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(int $id, array $data): PostDTO
    {
        $post = $this->postRepository->getById($id);

        if (!$post) {
            throw new \Exception('Post not found');
        }

        $this->postRepository->update($post, $data);

        $this->redisCacheService->delete(config('redis_keys.posts'));

        return new PostDTO(
            $post->id,
            $post->userId,
            $post->title,
            $post->slug,
            $post->content,
            $post->categoryId,
            $post->createdAt,
            $post->updatedAt
        );
    }
}
