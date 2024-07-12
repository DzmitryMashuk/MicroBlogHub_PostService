<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;

class GetByIdCommand
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function execute(int $id): CategoryDTO
    {
        $category = $this->categoryRepository->getById($id);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        return new CategoryDTO(
            $category->id,
            $category->name,
            $category->createdAt,
            $category->updatedAt
        );
    }
}
