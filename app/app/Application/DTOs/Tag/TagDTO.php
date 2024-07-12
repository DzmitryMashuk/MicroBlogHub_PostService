<?php

declare(strict_types=1);

namespace App\Application\DTOs\Tag;

class TagDTO
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?string $createdAt,
        public ?string $updatedAt
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? null,
            $data['createdAt'] ?? null,
            $data['updatedAt'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'createdAt'  => $this->createdAt,
            'updatedAt'  => $this->updatedAt,
        ];
    }
}
