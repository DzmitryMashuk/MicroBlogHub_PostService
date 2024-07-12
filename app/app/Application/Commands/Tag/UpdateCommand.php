<?php

declare(strict_types=1);

namespace App\Application\Commands\Tag;

use App\Application\DTOs\Tag\TagDTO;
use App\Domain\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class UpdateCommand
{
    public function __construct(private TagRepositoryInterface $tagRepository)
    {
    }

    public function execute(int $id, array $data): TagDTO
    {
        $tag = $this->tagRepository->getById($id);
        $this->tagRepository->update($tag, $data);

        Redis::del(config('redis_keys.tags'));

        return new TagDTO(
            $tag->id,
            $tag->name,
            $tag->createdAt,
            $tag->updatedAt
        );
    }
}
