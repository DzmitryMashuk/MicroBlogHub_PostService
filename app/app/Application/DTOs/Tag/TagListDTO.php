<?php

declare(strict_types=1);

namespace App\Application\DTOs\Tag;

class TagListDTO
{
    public function __construct(public array $tags)
    {
    }

    public static function fromArray(array $data): self
    {
        $tags = array_map(function ($tag) {
            return TagDTO::fromArray($tag);
        }, $data);

        return new self($tags);
    }

    public function toArray(): array
    {
        return array_map(function (TagDTO $tagDTO) {
            return $tagDTO->toArray();
        }, $this->tags);
    }
}
