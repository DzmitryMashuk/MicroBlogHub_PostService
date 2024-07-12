<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Commands\Category\CreateCommand;
use App\Application\Commands\Category\DeleteCommand;
use App\Application\Commands\Category\GetAllCommand;
use App\Application\Commands\Category\GetByIdCommand;
use App\Application\Commands\Category\UpdateCommand;
use App\Application\DTOs\Category\CategoryDTO;
use App\Application\DTOs\Category\CategoryListDTO;

class CategoryService
{
    public function __construct(
        private GetAllCommand $getAllCommand,
        private GetByIdCommand $getByIdCommand,
        private CreateCommand $createCommand,
        private UpdateCommand $updateCommand,
        private DeleteCommand $deleteCommand
    ) {
    }

    public function getAll(): CategoryListDTO
    {
        return $this->getAllCommand->execute();
    }

    public function getById(int $id): CategoryDTO
    {
        return $this->getByIdCommand->execute($id);
    }

    public function create(array $data): CategoryDTO
    {
        return $this->createCommand->execute($data);
    }

    public function update(int $id, array $data): CategoryDTO
    {
        return $this->updateCommand->execute($id, $data);
    }

    public function delete(int $id): void
    {
        $this->deleteCommand->execute($id);
    }
}
