<?php

declare(strict_types=1);

namespace App\Application\DTOs\Category;

class CategoryListDTO
{
    public function __construct(public array $categories)
    {
    }

    public static function fromArray(array $data): self
    {
        $categories = array_map(function ($category) {
            return CategoryDTO::fromArray($category);
        }, $data);

        return new self($categories);
    }

    public function toArray(): array
    {
        return array_map(function (CategoryDTO $categoryDTO) {
            return $categoryDTO->toArray();
        }, $this->categories);
    }
}
