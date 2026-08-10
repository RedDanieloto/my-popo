<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLoad extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'amount_paid',
        'price_per_liter',
        'liters',
        'is_full_tank',
        'date',
        'note',
    ];

    protected $casts = [
        'amount_paid' => 'float',
        'price_per_liter' => 'float',
        'liters' => 'float',
        'is_full_tank' => 'boolean',
        'date' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
