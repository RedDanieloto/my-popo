<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'year',
        'tank_capacity',
        'current_liters',
        'avg_consumption',
        'initial_avg_consumption',
    ];

    protected $casts = [
        'year' => 'integer',
        'tank_capacity' => 'float',
        'current_liters' => 'float',
        'avg_consumption' => 'float',
        'initial_avg_consumption' => 'float',
    ];

    public function fuelLoads(): HasMany
    {
        return $this->hasMany(FuelLoad::class)->orderBy('date', 'desc');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class)->orderBy('start_time', 'desc');
    }

    /**
     * Autonomía estimada en kilómetros.
     */
    public function getAutonomyKmAttribute(): float
    {
        return round($this->current_liters * $this->avg_consumption, 1);
    }

    /**
     * Porcentaje actual de combustible en el tanque.
     */
    public function getFuelPercentageAttribute(): float
    {
        if ($this->tank_capacity <= 0) {
            return 0;
        }

        return min(100.0, round(($this->current_liters / $this->tank_capacity) * 100, 1));
    }

    /**
     * Alerta: 7 litros o menos (Reserva Pointer 2005 es 6-8 Litros).
     */
    public function getIsLowFuelAttribute(): bool
    {
        return $this->current_liters <= 7.0;
    }

    /**
     * Alerta: autonomía menor a 50 km.
     */
    public function getIsLowAutonomyAttribute(): bool
    {
        return $this->autonomy_km < 50.0;
    }
}
