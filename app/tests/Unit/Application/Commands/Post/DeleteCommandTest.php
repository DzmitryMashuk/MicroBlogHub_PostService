<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Post;

use App\Application\Commands\Post\DeleteCommand;
use App\Domain\Repositories\PostRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class DeleteCommandTest extends TestCase
{
    private PostRepositoryInterface $postRepository;
    private RedisCacheService $redisCacheService;
    private DeleteCommand $deleteCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepository    = $this->createMock(PostRepositoryInterface::class);
        $this->redisCacheService = $this->createMock(RedisCacheService::class);
        $this->deleteCommand     = new DeleteCommand($this->postRepository, $this->redisCacheService);
    }

    public function testExecuteDeletesPostAndClearsCache(): void
    {
        $postId = 1;

        $this->postRepository->expects($this->once())
            ->method('delete')
            ->with($postId);

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.posts')));

        $this->deleteCommand->execute($postId);
    }

    public function testExecuteDoesNotThrowExceptionOnNonExistentPost(): void
    {
        $postId = PHP_INT_MAX;

        $this->postRepository->expects($this->once())
            ->method('delete')
            ->with($postId);

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.posts')));

        $this->deleteCommand->execute($postId);
    }
}
