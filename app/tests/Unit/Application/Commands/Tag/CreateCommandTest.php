<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Tag;

use App\Application\Commands\Tag\CreateCommand;
use App\Application\DTOs\Tag\TagDTO;
use App\Domain\Models\Tag;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private RedisCacheService $redisCacheService;
    private CreateCommand $createCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tagRepository     = $this->createMock(TagRepositoryInterface::class);
        $this->redisCacheService = $this->createMock(RedisCacheService::class);
        $this->createCommand     = new CreateCommand($this->tagRepository, $this->redisCacheService);
    }

    public function testExecuteCreatesTagAndReturnsTagDto(): void
    {
        $tagData = [
            'name' => 'Test Tag',
        ];

        $tag            = new Tag();
        $tag->id        = 1;
        $tag->name      = 'Test Tag';
        $tag->createdAt = now();
        $tag->updatedAt = now();

        $this->tagRepository->expects($this->once())
            ->method('create')
            ->with($tagData)
            ->willReturn($tag);

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.tags')));

        $result = $this->createCommand->execute($tagData);

        $this->assertInstanceOf(TagDTO::class, $result);
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->name, $result->name);
        $this->assertEquals($tag->createdAt, $result->createdAt);
        $this->assertEquals($tag->updatedAt, $result->updatedAt);
    }
}
