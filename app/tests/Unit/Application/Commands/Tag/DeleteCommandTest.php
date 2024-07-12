<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Tag;

use App\Application\Commands\Tag\DeleteCommand;
use App\Domain\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class DeleteCommandTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private DeleteCommand $deleteCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tagRepository = $this->createMock(TagRepositoryInterface::class);
        $this->deleteCommand = new DeleteCommand($this->tagRepository);
    }

    public function testExecuteDeletesTagAndClearsCache(): void
    {
        $tagId = 1;

        $this->tagRepository->expects($this->once())
            ->method('delete')
            ->with($tagId);

        Redis::shouldReceive('del')
            ->once()
            ->with(config('redis_keys.tags'));

        $this->deleteCommand->execute($tagId);
    }
}
