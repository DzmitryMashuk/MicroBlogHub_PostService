<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Post;

use App\Application\Commands\Post\UpdateCommand;
use App\Application\DTOs\Post\PostDTO;
use App\Domain\Models\Post;
use App\Domain\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class UpdateCommandTest extends TestCase
{
    private PostRepositoryInterface $postRepository;
    private UpdateCommand $updateCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepository = $this->createMock(PostRepositoryInterface::class);
        $this->updateCommand  = new UpdateCommand($this->postRepository);
    }

    public function testExecuteUpdatesPostAndReturnsPostDto(): void
    {
        $postId     = 1;
        $updateData = [
            'title'   => 'Updated Title',
            'content' => 'Updated content',
        ];

        $post             = new Post();
        $post->id         = $postId;
        $post->userId     = 1;
        $post->title      = 'Original Title';
        $post->slug       = 'original-title';
        $post->content    = 'Original content';
        $post->categoryId = 1;
        $post->createdAt  = now();
        $post->updatedAt  = now();

        $this->postRepository->expects($this->once())
            ->method('getById')
            ->with($postId)
            ->willReturn($post);

        $this->postRepository->expects($this->once())
            ->method('update')
            ->with($post, $updateData)
            ->willReturnCallback(function ($post, $data) {
                foreach ($data as $key => $value) {
                    $post->$key = $value;
                }
                $post->updatedAt = now();
                return $post;
            });

        Redis::shouldReceive('del')
            ->once()
            ->with(config('redis_keys.posts'));

        $result = $this->updateCommand->execute($postId, $updateData);

        $this->assertInstanceOf(PostDTO::class, $result);
        $this->assertEquals($post->id, $result->id);
        $this->assertEquals($post->userId, $result->userId);
        $this->assertEquals($updateData['title'], $result->title);
        $this->assertEquals($post->slug, $result->slug);
        $this->assertEquals($updateData['content'], $result->content);
        $this->assertEquals($post->categoryId, $result->categoryId);
        $this->assertEquals($post->createdAt, $result->createdAt);
        $this->assertEquals($post->updatedAt, $result->updatedAt);
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        $this->expectException(\Exception::class);

        $postId     = PHP_INT_MAX;
        $updateData = [
            'title'   => 'Updated Title',
            'content' => 'Updated content',
        ];

        $this->postRepository->expects($this->once())
            ->method('getById')
            ->with($postId)
            ->will($this->throwException(new \Exception('Post not found')));

        $this->updateCommand->execute($postId, $updateData);
    }
}
