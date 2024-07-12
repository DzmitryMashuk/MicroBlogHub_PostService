<?php

declare(strict_types=1);

namespace App\Application\Commands\Post;

use App\Application\DTOs\Post\PostListDTO;
use App\Domain\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class GetAllCommand
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function execute(): PostListDTO
    {
        $posts = Redis::get(config('redis_keys.posts'));

        if ($posts) {
            return PostListDTO::fromArray(json_decode($posts));
        }

        $posts = $this->postRepository->getAll();

        Redis::set(config('redis_keys.posts'), json_encode($posts));

        return new PostListDTO($posts);
    }
}
