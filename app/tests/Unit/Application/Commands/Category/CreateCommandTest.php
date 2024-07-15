<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\CreateCommand;
use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Models\Category;
use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Services\RedisCacheService;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private RedisCacheService $redisCacheService;
    private CreateCommand $createCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->redisCacheService  = $this->createMock(RedisCacheService::class);
        $this->createCommand      = new CreateCommand($this->categoryRepository, $this->redisCacheService);
    }

    public function testExecuteCreatesCategoryAndReturnsCategoryDto(): void
    {
        $data = [
            'name' => 'New Category',
        ];

        $category            = new Category();
        $category->id        = 1;
        $category->name      = 'New Category';
        $category->createdAt = now();
        $category->updatedAt = now();

        $this->categoryRepository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($category);

        $this->redisCacheService->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(config('redis_keys.categories')));

        $result = $this->createCommand->execute($data);

        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($category->name, $result->name);
        $this->assertEquals($category->createdAt, $result->createdAt);
        $this->assertEquals($category->updatedAt, $result->updatedAt);
    }
}
