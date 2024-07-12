<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Tag;

interface TagRepositoryInterface
{
    /**
     * @return Tag[]
     */
    public function getAll(): array;

    public function getById(int $id): Tag;

    public function create(array $data): Tag;

    public function update(Tag $tag, array $data): Tag;

    public function delete(int $id): bool;
}
