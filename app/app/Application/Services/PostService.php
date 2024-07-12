<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Commands\Post\CreateCommand;
use App\Application\Commands\Post\DeleteCommand;
use App\Application\Commands\Post\GetAllCommand;
use App\Application\Commands\Post\GetByIdCommand;
use App\Application\Commands\Post\UpdateCommand;
use App\Application\DTOs\Post\PostDTO;
use App\Application\DTOs\Post\PostListDTO;

class PostService
{
    public function __construct(
        private GetAllCommand $getAllCommand,
        private GetByIdCommand $getByIdCommand,
        private CreateCommand $createCommand,
        private UpdateCommand $updateCommand,
        private DeleteCommand $deleteCommand
    ) {
    }

    public function getAll(): PostListDTO
    {
        return $this->getAllCommand->execute();
    }

    public function getById(int $id): PostDTO
    {
        return $this->getByIdCommand->execute($id);
    }

    public function create(array $data): PostDTO
    {
        return $this->createCommand->execute($data);
    }

    public function update(int $id, array $data): PostDTO
    {
        return $this->updateCommand->execute($id, $data);
    }

    public function delete(int $id): void
    {
        $this->deleteCommand->execute($id);
    }
}
