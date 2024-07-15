<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Tag;

use App\Application\Commands\Tag\GetAllCommand;
use App\Application\DTOs\Tag\TagDTO;
use App\Application\DTOs\Tag\TagListDTO;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class GetAllCommandTest extends TestCase
{
    private TagRepositoryInterface $tagRepository;
    private RedisCacheService $redisCacheService;
    private GetAllCommand $getAllCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tagRepository     = $this->createMock(TagRepositoryInterface::class);
        $this->redisCacheService = $this->createMock(RedisCacheService::class);
        $this->getAllCommand     = new GetAllCommand($this->tagRepository, $this->redisCacheService);
    }

    public function testExecuteReturnsCachedTags(): void
    {
        $cachedTags = [
            ['id' => 1, 'name' => 'Tag One'],
            ['id' => 2, 'name' => 'Tag Two'],
        ];

        $this->redisCacheService->expects($this->once())
            ->method('get')
            ->with(config('redis_keys.tags'))
            ->willReturn(json_encode($cachedTags));

        $result = $this->getAllCommand->execute();

        $this->assertInstanceOf(TagListDTO::class, $result);
        $this->assertCount(2, $result->tags);
        $this->assertEquals($cachedTags[0]['id'], $result->tags[0]->id);
        $this->assertEquals($cachedTags[1]['name'], $result->tags[1]->name);
    }

    public function testExecuteRetrievesTagsFromRepositoryAndCachesThem(): void
    {
        $tags = [
            new TagDTO(1, 'Tag One', null, null),
            new TagDTO(2, 'Tag Two', null, null),
        ];

        $this->tagRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($tags);

        $this->redisCacheService->expects($this->once())
            ->method('get')
            ->with(config('redis_keys.tags'))
            ->willReturn(null);

        $this->redisCacheService->expects($this->once())
            ->method('set')
            ->with(
                config('redis_keys.tags'),
                json_encode($tags)
            );

        $result = $this->getAllCommand->execute();

        $this->assertInstanceOf(TagListDTO::class, $result);
        $this->assertCount(2, $result->tags);
        $this->assertEquals($tags[0]->id, $result->tags[0]->id);
        $this->assertEquals($tags[1]->name, $result->tags[1]->name);
    }
}
