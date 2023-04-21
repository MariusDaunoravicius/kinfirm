<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProductDTO
{
    /**
     * @param  Collection<string,TagDTO>  $tags
     */
    public function __construct(
        private readonly string $sku,
        private readonly string $description,
        private readonly string $size,
        private readonly string $photo,
        private readonly Collection $tags,
        private readonly ?Carbon $updatedAt,
    ) {
    }

    public function getSKU(): string
    {
        return $this->sku;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    /**
     * @return Collection<string,TagDTO>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }
}
