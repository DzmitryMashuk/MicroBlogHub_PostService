<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Application\DTOs\Post\PostDTO;
use App\Domain\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class UpdateCommand
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function execute(int $id, array $data): PostDTO
    {
        $post = $this->postRepository->getById($id);
        $this->postRepository->update($post, $data);

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
