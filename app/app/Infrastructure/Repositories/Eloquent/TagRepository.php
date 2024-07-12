<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Models\Tag;
use App\Domain\Repositories\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface
{
    public function getAll(): array
    {
        return Tag::all()->toArray();
    }

    public function getById(int $id): ?Tag
    {
        return Tag::find($id);
    }

    public function create(array $data): Tag
    {
        return Tag::create($data);
    }

    public function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);

        return $tag;
    }

    public function delete(int $id): bool
    {
        return (bool) Tag::destroy($id);
    }
}
