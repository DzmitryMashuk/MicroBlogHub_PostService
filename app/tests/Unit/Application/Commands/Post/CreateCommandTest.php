<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Post;

use App\Application\Commands\Post\CreateCommand;
use App\Application\DTOs\Post\PostDTO;
use App\Domain\Models\Post;
use App\Domain\Repositories\PostRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    private PostRepositoryInterface $postRepository;
    private RedisCacheService $redisCacheService;
    private CreateCommand $createCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepository    = $this->createMock(PostRepositoryInterface::class);
        $this->redisCacheService = $this->createMock(RedisCacheService::class);
        $this->createCommand     = new CreateCommand($this->postRepository, $this->redisCacheService);
    }

    public function testExecuteCreatesPostAndReturnsPostDto(): void
    {
        $postData = [
            'userId'     => 1,
            'title'      => 'Test Title',
            'slug'       => 'test-title',
            'content'    => 'Test content',
            'categoryId' => 1,
        ];

        $createdPost             = new Post();
        $createdPost->id         = 1;
        $createdPost->userId     = $postData['userId'];
        $createdPost->title      = $postData['title'];
        $createdPost->slug       = $postData['slug'];
        $createdPost->content    = $postData['content'];
        $createdPost->categoryId = $postData['categoryId'];
        $createdPost->createdAt  = now();
        $createdPost->updatedAt  = now();

        $this->postRepository->expects($this->once())
            ->method('create')
            ->with($postData)
            ->willReturn($createdPost);

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.posts')));

        $result = $this->createCommand->execute($postData);

        $this->assertInstanceOf(PostDTO::class, $result);
        $this->assertEquals($createdPost->id, $result->id);
        $this->assertEquals($createdPost->userId, $result->userId);
        $this->assertEquals($createdPost->title, $result->title);
        $this->assertEquals($createdPost->slug, $result->slug);
        $this->assertEquals($createdPost->content, $result->content);
        $this->assertEquals($createdPost->categoryId, $result->categoryId);
        $this->assertEquals($createdPost->createdAt, $result->createdAt);
        $this->assertEquals($createdPost->updatedAt, $result->updatedAt);
    }
}
