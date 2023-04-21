<?php

namespace Tests;

use App\DTO\ProductDTO;
use App\DTO\TagDTO;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  array<string,string>  $variables
     * @param  Collection<int,TagDTO>|null  $tags
     */
    protected function mockProductDTO(array $variables = [], ?Collection $tags = null): ProductDTO
    {
        $sku = fake()->word;
        $description = fake()->text;
        $size = fake()->word;
        $photo = fake()->imageUrl;
        $updatedAt = new Carbon();

        $data = array_merge(compact(
            'sku',
            'description',
            'size',
            'photo',
            'updatedAt',
        ), $variables);

        return new ProductDTO(
            sku: $data['sku'],
            description: $data['description'],
            size: $data['size'],
            photo: $data['photo'],
            tags: $tags ?: collect(),
            updatedAt: $data['updatedAt'],
        );
    }

    protected function mockTagDTO(array $variables = []): TagDTO
    {
        $title = fake()->word;

        $data = array_merge(compact(
            'title',
        ), $variables);

        return new TagDTO(
            title: $data['title'],
        );
    }
}
