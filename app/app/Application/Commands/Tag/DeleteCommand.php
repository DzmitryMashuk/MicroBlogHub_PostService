<?php

declare(strict_types=1);

namespace App\Application\Commands\Tag;

use App\Domain\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class DeleteCommand
{
    public function __construct(private TagRepositoryInterface $tagRepository)
    {
    }

    public function execute(int $id): void
    {
        $this->tagRepository->delete($id);

        Redis::del(config('redis_keys.tags'));
    }
}
