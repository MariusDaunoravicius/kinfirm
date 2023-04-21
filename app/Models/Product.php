<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'sku',
        'description',
        'size',
        'photo',
        'product_updated_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'product_updated_at' => 'date',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}