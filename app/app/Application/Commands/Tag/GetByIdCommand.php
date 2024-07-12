<?php

declare(strict_types=1);

namespace App\Application\Commands\Tag;

use App\Application\DTOs\Tag\TagDTO;
use App\Domain\Repositories\TagRepositoryInterface;

class GetByIdCommand
{
    public function __construct(private TagRepositoryInterface $tagRepository)
    {
    }

    public function execute(int $id): TagDTO
    {
        $tag = $this->tagRepository->getById($id);

        return new TagDTO(
            $tag->id,
            $tag->name,
            $tag->createdAt,
            $tag->updatedAt
        );
    }
}
