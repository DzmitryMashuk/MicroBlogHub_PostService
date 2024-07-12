<?php

declare(strict_types=1);

namespace App\Application\Commands\Category;

use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class DeleteCommand
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function execute(int $id): void
    {
        $this->categoryRepository->delete($id);

        Redis::del(config('redis_keys.categories'));
    }
}
