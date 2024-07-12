<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Post;

interface PostRepositoryInterface
{
    /**
     * @return Post[]
     */
    public function getAll(): array;

    public function getById(int $id): Post;

    public function create(array $data): Post;

    public function update(Post $post, array $data): Post;

    public function delete(int $id): bool;
}
