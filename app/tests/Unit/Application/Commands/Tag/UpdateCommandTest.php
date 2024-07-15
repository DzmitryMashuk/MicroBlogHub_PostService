<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Tag;

use App\Application\Commands\Tag\UpdateCommand;
use App\Application\DTOs\Tag\TagDTO;
use App\Domain\Models\Tag;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class UpdateCommandTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private RedisCacheService $redisCacheService;
    private UpdateCommand $updateCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tagRepository     = $this->createMock(TagRepositoryInterface::class);
        $this->redisCacheService = $this->createMock(RedisCacheService::class);
        $this->updateCommand     = new UpdateCommand($this->tagRepository, $this->redisCacheService);
    }

    public function testExecuteUpdatesTagAndReturnsTagDto(): void
    {
        $tagId      = 1;
        $updateData = [
            'name' => 'Updated Tag',
        ];

        $tag            = new Tag();
        $tag->id        = $tagId;
        $tag->name      = 'Original Tag';
        $tag->createdAt = now();
        $tag->updatedAt = now();

        $this->tagRepository->expects($this->once())
            ->method('getById')
            ->with($tagId)
            ->willReturn($tag);

        $this->tagRepository->expects($this->once())
            ->method('update')
            ->with($tag, $updateData)
            ->willReturnCallback(function ($tag, $data) {
                foreach ($data as $key => $value) {
                    $tag->$key = $value;
                }
                $tag->updatedAt = now();
                return $tag;
            });

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.tags')));

        $result = $this->updateCommand->execute($tagId, $updateData);

        $this->assertInstanceOf(TagDTO::class, $result);
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($updateData['name'], $result->name);
        $this->assertEquals($tag->createdAt, $result->createdAt);
        $this->assertEquals($tag->updatedAt, $result->updatedAt);
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        $this->expectException(\Exception::class);

        $tagId      = PHP_INT_MAX;
        $updateData = [
            'name' => 'Updated name',
        ];

        $this->tagRepository->expects($this->once())
            ->method('getById')
            ->with($tagId)
            ->will($this->throwException(new \Exception('Tag not found')));

        $this->updateCommand->execute($tagId, $updateData);
    }
}
