<?php

declare(strict_types=1);

namespace App\Application\DTOs\Post;

class PostDTO
{
    public function __construct(
        public ?int $id,
        public ?int $userId,
        public ?string $title,
        public ?string $slug,
        public ?string $content,
        public ?int $categoryId,
        public ?string $createdAt,
        public ?string $updatedAt
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['userId'] ?? null,
            $data['title'] ?? null,
            $data['slug'] ?? null,
            $data['content'] ?? null,
            $data['categoryId'] ?? null,
            $data['createdAt'] ?? null,
            $data['updatedAt'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'userId'     => $this->userId,
            'title'      => $this->title,
            'slug'       => $this->slug,
            'content'    => $this->content,
            'categoryId' => $this->categoryId,
            'createdAt'  => $this->createdAt,
            'updatedAt'  => $this->updatedAt,
        ];
    }
}
