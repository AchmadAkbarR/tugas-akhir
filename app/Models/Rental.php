<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'air_conditioner_id',
        'quantity',
        'payment_method',
        'payment_status',
        'payment_reference',
        'rental_start',
        'rental_end',
        'rental_type',
        'total_price',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'notes',
    ];

    protected $casts = [
        'rental_start' => 'datetime',
        'rental_end' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function airConditioner(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'air_conditioner_id');
    }
}
