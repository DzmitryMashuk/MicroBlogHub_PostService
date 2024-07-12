<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function getAll(): array;

    public function getById(int $id): Category;

    public function create(array $data): Category;

    public function update(Category $category, array $data): Category;

    public function delete(int $id): bool;
}
