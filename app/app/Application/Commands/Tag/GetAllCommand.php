<?php

declare(strict_types=1);

namespace App\Application\Commands\Tag;

use App\Application\DTOs\Tag\TagListDTO;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;

class GetAllCommand
{
    public function __construct(
        private TagRepositoryInterface $tagRepository,
        private RedisCacheService $redisCacheService
    ) {
    }

    public function execute(): TagListDTO
    {
        $tags = $this->redisCacheService->get(config('redis_keys.tags'));

        if ($tags) {
            return TagListDTO::fromArray(json_decode($tags, true));
        }

        $tags = $this->tagRepository->getAll();

        $this->redisCacheService->set(config('redis_keys.tags'), json_encode($tags));

        return new TagListDTO($tags);
    }
}
