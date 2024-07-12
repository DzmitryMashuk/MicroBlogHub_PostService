<?php

declare(strict_types=1);

namespace App\Application\DTOs\Post;

class PostListDTO
{
    public function __construct(public array $posts)
    {
    }

    public static function fromArray(array $data): self
    {
        $posts = array_map(function ($post) {
            return PostDTO::fromArray($post);
        }, $data);

        return new self($posts);
    }

    public function toArray(): array
    {
        return array_map(function (PostDTO $postDTO) {
            return $postDTO->toArray();
        }, $this->posts);
    }
}
