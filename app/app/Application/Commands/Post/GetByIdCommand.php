<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Application\DTOs\Post\PostDTO;
use App\Domain\Repositories\PostRepositoryInterface;

class GetByIdCommand
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function execute(int $id): PostDTO
    {
        $post = $this->postRepository->getById($id);

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
