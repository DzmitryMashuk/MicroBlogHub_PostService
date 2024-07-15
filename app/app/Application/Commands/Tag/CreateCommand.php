<?php

declare(strict_types=1);

namespace App\Application\Commands\Tag;

use App\Application\DTOs\Tag\TagDTO;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class CreateCommand
{
    public function __construct(
        private TagRepositoryInterface $tagRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(array $data): TagDTO
    {
        $tag = $this->tagRepository->create($data);

        $this->redisCacheService->delete(config('redis_keys.tags'));

        return new TagDTO(
            $tag->id,
            $tag->name,
            $tag->createdAt,
            $tag->updatedAt
        );
    }
}
