<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class CreateCommand
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function execute(array $data): CategoryDTO
    {
        $category = $this->categoryRepository->create($data);

        Redis::del(config('redis_keys.categories'));

        return new CategoryDTO(
            $category->id,
            $category->name,
            $category->createdAt,
            $category->updatedAt,
        );
    }
}
