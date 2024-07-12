<?php

declare(strict_types=1);

namespace App\Application\Commands\Tag;

use App\Application\DTOs\Tag\TagListDTO;
use App\Domain\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class GetAllCommand
{
    public function __construct(private TagRepositoryInterface $tagRepository)
    {
    }

    public function execute(): TagListDTO
    {
        $tags = Redis::get(config('redis_keys.tags'));

        if ($tags) {
            return TagListDTO::fromArray(json_decode($tags));
        }

        $tags = $this->tagRepository->getAll();

        Redis::set(config('redis_keys.tags'), json_encode($tags));

        return new TagListDTO($tags);
    }
}
