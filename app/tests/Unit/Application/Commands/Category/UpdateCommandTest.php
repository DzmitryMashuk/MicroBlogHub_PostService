<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\UpdateCommand;
use App\Application\DTOs\Category\CategoryDTO;
use App\Domain\Models\Category;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class UpdateCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private UpdateCommand $updateCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->updateCommand      = new UpdateCommand($this->categoryRepository);
    }

    public function testExecuteUpdatesCategoryAndReturnsCategoryDto(): void
    {
        $categoryId = 1;

        $category            = new Category();
        $category->id        = $categoryId;
        $category->name      = 'Old Category';
        $category->createdAt = now();
        $category->updatedAt = now();
        $updateData          = ['name' => 'Updated Category'];

        $this->categoryRepository->expects($this->once())
            ->method('getById')
            ->with($categoryId)
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method('update')
            ->with($category, $updateData)
            ->willReturnCallback(function ($category, $data) {
                foreach ($data as $key => $value) {
                    $category->$key = $value;
                }
                $category->updatedAt = now();
                return $category;
            });

        $result = $this->updateCommand->execute($categoryId, $updateData);

        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($updateData['name'], $result->name);
        $this->assertEquals($category->createdAt, $result->createdAt);
        $this->assertEquals($category->updatedAt, $result->updatedAt);
    }

    public function testExecuteThrowsExceptionWhenPostNotFound(): void
    {
        $this->expectException(\Exception::class);

        $categoryId = PHP_INT_MAX;
        $updateData = [
            'name' => 'Updated name',
        ];

        $this->categoryRepository->expects($this->once())
            ->method('getById')
            ->with($categoryId)
            ->will($this->throwException(new \Exception('Category not found')));

        $this->updateCommand->execute($categoryId, $updateData);
    }
}
