<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Commands\Tag\CreateCommand;
use App\Application\Commands\Tag\DeleteCommand;
use App\Application\Commands\Tag\GetAllCommand;
use App\Application\Commands\Tag\GetByIdCommand;
use App\Application\Commands\Tag\UpdateCommand;
use App\Application\DTOs\Tag\TagDTO;
use App\Application\DTOs\Tag\TagListDTO;

class TagService
{
    public function __construct(
        private GetAllCommand $getAllCommand,
        private GetByIdCommand $getByIdCommand,
        private CreateCommand $createCommand,
        private UpdateCommand $updateCommand,
        private DeleteCommand $deleteCommand
    ) {
    }

    public function getAll(): TagListDTO
    {
        return $this->getAllCommand->execute();
    }

    public function getById(int $id): TagDTO
    {
        return $this->getByIdCommand->execute($id);
    }

    public function create(array $data): TagDTO
    {
        return $this->createCommand->execute($data);
    }

    public function update(int $id, array $data): TagDTO
    {
        return $this->updateCommand->execute($id, $data);
    }

    public function delete(int $id): void
    {
        $this->deleteCommand->execute($id);
    }
}
