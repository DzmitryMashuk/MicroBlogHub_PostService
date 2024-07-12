<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Post;

use App\Application\Commands\Post\GetByIdCommand;
use App\Application\DTOs\Post\PostDTO;
use App\Domain\Models\Post;
use App\Domain\Repositories\PostRepositoryInterface;
use Tests\TestCase;

class GetByIdCommandTest extends TestCase
{
    private PostRepositoryInterface $postRepository;
    private GetByIdCommand $getByIdCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepository = $this->createMock(PostRepositoryInterface::class);
        $this->getByIdCommand = new GetByIdCommand($this->postRepository);
    }

    public function testExecuteReturnsPostDto()
    {
        $postId = 1;

        $post             = new Post();
        $post->id         = $postId;
        $post->userId     = 1;
        $post->title      = 'Test Title';
        $post->slug       = 'test-title';
        $post->content    = 'Test content';
        $post->categoryId = 1;
        $post->createdAt  = now();
        $post->updatedAt  = now();

        $this->postRepository->expects($this->once())
            ->method('getById')
            ->with($postId)
            ->willReturn($post);

        $result = $this->getByIdCommand->execute($postId);

        $this->assertInstanceOf(PostDTO::class, $result);
        $this->assertEquals($post->id, $result->id);
        $this->assertEquals($post->userId, $result->userId);
        $this->assertEquals($post->title, $result->title);
        $this->assertEquals($post->slug, $result->slug);
        $this->assertEquals($post->content, $result->content);
        $this->assertEquals($post->categoryId, $result->categoryId);
        $this->assertEquals($post->createdAt, $result->createdAt);
        $this->assertEquals($post->updatedAt, $result->updatedAt);
    }

    public function testExecuteThrowsExceptionWhenPostNotFound()
    {
        $this->expectException(\Exception::class);

        $postId = PHP_INT_MAX;

        $this->postRepository->expects($this->once())
            ->method('getById')
            ->with($postId)
            ->will($this->throwException(new \Exception('Post not found')));

        $this->getByIdCommand->execute($postId);
    }
}
