<?php

declare(strict_types=1);

namespace App\Factories;

use App\DTO\TagDTO;

class TagDTOFactory
{
    /**
     * @param  array<string,string>  $data
     */
    public function createFromArray(array $data): TagDTO
    {
        return new TagDTO(
            title: $data['title'],
        );
    }
}
