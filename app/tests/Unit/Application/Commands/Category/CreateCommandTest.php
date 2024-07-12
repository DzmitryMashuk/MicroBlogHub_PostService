<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\CreateCommand;
use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Models\Category;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private CreateCommand $createCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->createCommand      = new CreateCommand($this->categoryRepository);
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

        Redis::shouldReceive('del')
            ->once()
            ->with(config('redis_keys.categories'));

        $result = $this->createCommand->execute($data);

        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($category->name, $result->name);
        $this->assertEquals($category->createdAt, $result->createdAt);
        $this->assertEquals($category->updatedAt, $result->updatedAt);
    }
}
