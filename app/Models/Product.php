<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @var array<string,string>
     */
    protected $casts = [
        'product_updated_at' => 'date',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(related: Tag::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(related: Stock::class, foreignKey: 'sku', localKey: 'sku');
    }
}
