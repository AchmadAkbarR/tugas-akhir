<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $table = 'air_conditioners';

    protected $fillable = [
        'category_id',
        'model',
        'description',
        'cooling_capacity',
        'type',
        'rental_price_per_day',
        'rental_price_per_month',
        'status',
        'serial_number',
        'image',
        'stock',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'air_conditioner_id');
    }
}
