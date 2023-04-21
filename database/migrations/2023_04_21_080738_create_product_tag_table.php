<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_tag', function (Blueprint $table) {
            $table->id();

            $table->ulid('product_id');
            $table->ulid('tag_id');

            $table->unique(['product_id', 'tag_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('product_tag');
    }
};
