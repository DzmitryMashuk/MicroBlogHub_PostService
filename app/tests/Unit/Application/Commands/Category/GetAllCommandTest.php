<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\GetAllCommand;
use App\Application\DTOs\Category\CategoryListDTO;
use App\Domain\Models\Category;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class GetAllCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private RedisCacheService $redisCacheService;
    private GetAllCommand $getAllCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->redisCacheService  = $this->createMock(RedisCacheService::class);
        $this->getAllCommand      = new GetAllCommand($this->categoryRepository, $this->redisCacheService);
    }

    public function testExecuteReturnsCachedCategories(): void
    {
        $categories = [
            [
                'id'        => 1,
                'name'      => 'Category 1',
                'createdAt' => now()->toISOString(),
                'updatedAt' => now()->toISOString(),
            ],
        ];

        $this->redisCacheService
            ->method('get')
            ->with(config('redis_keys.categories'))
            ->willReturn(json_encode($categories));

        $result = $this->getAllCommand->execute();

        $this->assertInstanceOf(CategoryListDTO::class, $result);
        $this->assertEquals($categories, $result->toArray());
    }

    public function testExecuteRetrievesCategoriesFromRepositoryAndCachesThem(): void
    {
        $categoryModels = [
            new Category(['id' => 1, 'name' => 'Category 1']),
            new Category(['id' => 2, 'name' => 'Category 2']),
        ];

        $this->categoryRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($categoryModels);

        $this->redisCacheService->method('get')
            ->with(config('redis_keys.categories'))
            ->willReturn(null);

        $this->redisCacheService->expects($this->once())
            ->method('set')
            ->with(
                config('redis_keys.categories'),
                json_encode($categoryModels)
            );

        $result = $this->getAllCommand->execute();

        $this->assertInstanceOf(CategoryListDTO::class, $result);
        $this->assertCount(2, $result->categories);
        $this->assertEquals($categoryModels[0]->id, $result->categories[0]->id);
        $this->assertEquals($categoryModels[1]->name, $result->categories[1]->name);
    }
}
