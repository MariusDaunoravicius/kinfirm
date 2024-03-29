<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'city_id',
        'sku',
        'stock',
    ];

    /**
     * @var array<string,string>
     */
    protected $casts = [
        'stock' => 'int',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
