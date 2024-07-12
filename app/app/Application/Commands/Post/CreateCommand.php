<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Application\DTOs\Post\PostDTO;
use App\Domain\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class CreateCommand
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function execute(array $data): PostDTO
    {
        $post = $this->postRepository->create($data);

        Redis::del(config('redis_keys.posts'));

        return new PostDTO(
            $post->id,
            $post->userId,
            $post->title,
            $post->slug,
            $post->content,
            $post->categoryId,
            $post->createdAt,
            $post->updatedAt,
        );
    }
}
