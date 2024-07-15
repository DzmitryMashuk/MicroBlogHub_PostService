<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Post;

use App\Application\Commands\Post\GetAllCommand;
use App\Application\DTOs\Post\PostListDTO;
use App\Domain\Repositories\PostRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class GetAllCommandTest extends TestCase
{
    private PostRepositoryInterface $postRepository;
    private RedisCacheService $redisCacheService;
    private GetAllCommand $getAllCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepository    = $this->createMock(PostRepositoryInterface::class);
        $this->redisCacheService = $this->createMock(RedisCacheService::class);
        $this->getAllCommand     = new GetAllCommand($this->postRepository, $this->redisCacheService);
    }

    public function testExecuteReturnsCachedPosts(): void
    {
        $cachedPosts = json_encode([
            [
                'user_id'     => 1,
                'category_id' => 1,
                'title'       => 'Post 1',
                'slug'        => 'post_1',
                'content'     => 'content 1',
            ],
            [
                'user_id'     => 2,
                'category_id' => 2,
                'title'       => 'Post 2',
                'slug'        => 'post_2',
                'content'     => 'content 2',
            ],
        ]);

        $this->redisCacheService->method('get')
            ->with(config('redis_keys.posts'))
            ->willReturn($cachedPosts);

        $result = $this->getAllCommand->execute();

        $this->assertInstanceOf(PostListDTO::class, $result);
        $this->assertCount(2, $result->posts);
    }

    public function testExecuteRetrievesPostsFromRepositoryAndCachesThem(): void
    {
        $posts = [
            [
                'id'          => 1,
                'user_id'     => 1,
                'category_id' => 1,
                'title'       => 'Post 1',
                'slug'        => 'post_1',
                'content'     => 'content 1',
            ],
            [
                'id'          => 2,
                'user_id'     => 2,
                'category_id' => 2,
                'title'       => 'Post 2',
                'slug'        => 'post_2',
                'content'     => 'content 2',
            ],
        ];

        $this->redisCacheService->method('get')
            ->with(config('redis_keys.posts'))
            ->willReturn(null);

        $this->redisCacheService->expects($this->once())
            ->method('set')
            ->with(
                config('redis_keys.posts'),
                json_encode($posts)
            );

        $this->postRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($posts);

        $result = $this->getAllCommand->execute();

        $this->assertInstanceOf(PostListDTO::class, $result);
        $this->assertCount(2, $result->posts);
    }
}
