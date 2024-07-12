<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Application\DTOs\Category\CategoryListDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class GetAllCommand
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function execute(): CategoryListDTO
    {
        $categories = Redis::get(config('redis_keys.categories'));

        if ($categories) {
            return CategoryListDTO::fromArray(json_decode($categories, true));
        }

        $categories = $this->categoryRepository->getAll();

        Redis::set(config('redis_keys.categories'), json_encode($categories));

        return new CategoryListDTO($categories);
    }
}
