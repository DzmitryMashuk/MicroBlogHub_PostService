<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Tag;

use App\Application\Commands\Tag\GetByIdCommand;
use App\Application\DTOs\Tag\TagDTO;
use App\Domain\Models\Tag;
use App\Domain\Repositories\TagRepositoryInterface;
use Tests\TestCase;

class GetByIdCommandTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private GetByIdCommand $getByIdCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tagRepository  = $this->createMock(TagRepositoryInterface::class);
        $this->getByIdCommand = new GetByIdCommand($this->tagRepository);
    }

    public function testExecuteReturnsTagDto(): void
    {
        $tagId = 1;

        $tag            = new Tag();
        $tag->id        = $tagId;
        $tag->name      = 'Test Tag';
        $tag->createdAt = now();
        $tag->updatedAt = now();

        $this->tagRepository->expects($this->once())
            ->method('getById')
            ->with($tagId)
            ->willReturn($tag);

        $result = $this->getByIdCommand->execute($tagId);

        $this->assertInstanceOf(TagDTO::class, $result);
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->name, $result->name);
        $this->assertEquals($tag->createdAt, $result->createdAt);
        $this->assertEquals($tag->updatedAt, $result->updatedAt);
    }

    public function testExecuteThrowsExceptionIfTagNotFound(): void
    {
        $this->expectException(\Exception::class);

        $tagId = PHP_INT_MAX;

        $this->tagRepository->expects($this->once())
            ->method('getById')
            ->with($tagId)
            ->will($this->throwException(new \Exception('Tag not found')));

        $this->getByIdCommand->execute($tagId);
    }
}
