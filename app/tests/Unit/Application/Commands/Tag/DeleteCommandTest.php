<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Tag;

use App\Application\Commands\Tag\DeleteCommand;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class DeleteCommandTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private RedisCacheService $redisCacheService;
    private DeleteCommand $deleteCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tagRepository     = $this->createMock(TagRepositoryInterface::class);
        $this->redisCacheService = $this->createMock(RedisCacheService::class);
        $this->deleteCommand     = new DeleteCommand($this->tagRepository, $this->redisCacheService);
    }

    public function testExecuteDeletesTagAndClearsCache(): void
    {
        $tagId = 1;

        $this->tagRepository->expects($this->once())
            ->method('delete')
            ->with($tagId);

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.tags')));

        $this->deleteCommand->execute($tagId);
    }
}
