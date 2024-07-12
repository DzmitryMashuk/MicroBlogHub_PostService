<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\Category;

use App\Application\Commands\Category\DeleteCommand;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class DeleteCommandTest extends TestCase
{
    private CategoryRepositoryInterface $categoryRepository;
    private DeleteCommand $deleteCommand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->deleteCommand      = new DeleteCommand($this->categoryRepository);
    }

    public function testExecuteDeletesCategoryAndClearsCache(): void
    {
        $categoryId = 1;

        $this->categoryRepository->expects($this->once())
            ->method('delete')
            ->with($categoryId);

        Redis::shouldReceive('del')
            ->once()
            ->with(config('redis_keys.categories'));

        $this->deleteCommand->execute($categoryId);
    }
}
