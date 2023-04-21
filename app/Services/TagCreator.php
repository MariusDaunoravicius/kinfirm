<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\TagDTO;
use App\Models\Tag;

class TagCreator
{
    public function create(TagDTO $tagDTO): Tag
    {
        return Tag::updateOrCreate(
            [
                'title' => $tagDTO->getTitle(),
            ],
        );
    }
}
