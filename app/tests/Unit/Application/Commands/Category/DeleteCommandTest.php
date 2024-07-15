<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\DeleteCommand;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class DeleteCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private RedisCacheService $redisCacheService;
    private DeleteCommand $deleteCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->redisCacheService  = $this->createMock(RedisCacheService::class);
        $this->deleteCommand      = new DeleteCommand($this->categoryRepository, $this->redisCacheService);
    }

    public function testExecuteDeletesCategoryAndClearsCache(): void
    {
        $categoryId = 1;

        $this->categoryRepository->expects($this->once())
            ->method('delete')
            ->with($categoryId);

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.categories')));

        $this->deleteCommand->execute($categoryId);
    }
}
