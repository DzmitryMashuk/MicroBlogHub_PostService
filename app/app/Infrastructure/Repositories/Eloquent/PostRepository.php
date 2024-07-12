<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Models\Post;
use App\Domain\Repositories\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    public function getAll(): array
    {
        return Post::all()->toArray();
    }

    public function getById(int $id): Post
    {
        return Post::find($id);
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);

        return $post;
    }

    public function delete(int $id): bool
    {
        return (bool) Post::destroy($id);
    }
}
