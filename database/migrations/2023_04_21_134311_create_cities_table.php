<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('name')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
