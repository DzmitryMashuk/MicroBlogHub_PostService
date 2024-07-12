<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\GetAllCommand;
use App\Application\DTOs\Category\CategoryListDTO;
use App\Domain\Models\Category;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class GetAllCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private GetAllCommand $getAllCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->getAllCommand      = new GetAllCommand($this->categoryRepository);
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

        Redis::shouldReceive('get')
            ->once()
            ->with(config('redis_keys.categories'))
            ->andReturn(json_encode($categories));

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

        Redis::shouldReceive('get')
            ->once()
            ->with(config('redis_keys.categories'))
            ->andReturn(null);

        Redis::shouldReceive('set')
            ->once()
            ->with(config('redis_keys.categories'), json_encode($categoryModels));

        $result = $this->getAllCommand->execute();

        $this->assertInstanceOf(CategoryListDTO::class, $result);
        $this->assertCount(2, $result->categories);
        $this->assertEquals($categoryModels[0]->id, $result->categories[0]->id);
        $this->assertEquals($categoryModels[1]->name, $result->categories[1]->name);
    }
}
