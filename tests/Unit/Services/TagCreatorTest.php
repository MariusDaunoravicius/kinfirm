<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\TagDTO;
use App\Services\TagCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
    {
        $title = fake()->word;

        $tagDTO = new TagDTO(
            title: $title,
        );

        $tag = (new TagCreator())->create(tagDTO: $tagDTO);

        self::assertEquals(
            expected: [
                'title' => $title,
            ],
            actual: $tag->only(
                attributes: [
                    'title',
                ],
            )
        );

        self::assertDatabaseHas(table: 'tags', data: ['title' => $title]);
    }
}
