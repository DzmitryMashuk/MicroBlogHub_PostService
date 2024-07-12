<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Domain\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class DeleteCommand
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function execute(int $id): void
    {
        $this->postRepository->delete($id);

        Redis::del(config('redis_keys.posts'));
    }
}
