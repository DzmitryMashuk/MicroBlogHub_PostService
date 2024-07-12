<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\GetByIdCommand;
use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Models\Category;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Tests\TestCase;

class GetByIdCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private GetByIdCommand $getByIdCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->getByIdCommand     = new GetByIdCommand($this->categoryRepository);
    }

    public function testExecuteReturnsCategoryDto(): void
    {
        $categoryId = 1;

        $category            = new Category();
        $category->id        = $categoryId;
        $category->name      = 'Test category';
        $category->createdAt = now();
        $category->updatedAt = now();

        $this->categoryRepository->expects($this->once())
            ->method('getById')
            ->with($categoryId)
            ->willReturn($category);

        $result = $this->getByIdCommand->execute($categoryId);

        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($category->name, $result->name);
        $this->assertEquals($category->createdAt, $result->createdAt);
        $this->assertEquals($category->updatedAt, $result->updatedAt);
    }

    public function testExecuteThrowsExceptionIfCategoryNotFound(): void
    {
        $categoryId = PHP_INT_MAX;

        $this->categoryRepository->expects($this->once())
            ->method('getById')
            ->with($categoryId)
            ->willThrowException(new \Exception('Category not found'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Category not found');

        $this->getByIdCommand->execute($categoryId);
    }
}
