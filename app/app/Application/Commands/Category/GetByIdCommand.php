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
        $categories = $this->categoryRepository->getById($id);

        return new CategoryDTO(
            $categories->id,
            $categories->name,
            $categories->createdAt,
            $categories->updatedAt
        );
    }
}
