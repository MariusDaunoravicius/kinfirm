<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'description',
        'size',
        'photo',
        'product_updated_at',
    ];

    protected $casts = [
        'product_updated_at' => 'date',
    ];
}
