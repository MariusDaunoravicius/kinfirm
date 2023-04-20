<?php

declare(strict_types=1);

namespace App\DTO;

class TagDTO
{
    public function __construct(private readonly string $title)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
