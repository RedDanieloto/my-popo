<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'start_time',
        'end_time',
        'distance_km',
        'liters_consumed',
        'status',
        'is_manual',
        'title',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'distance_km' => 'float',
        'liters_consumed' => 'float',
        'is_manual' => 'boolean',
        'start_lat' => 'float',
        'start_lng' => 'float',
        'end_lat' => 'float',
        'end_lng' => 'float',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Retorna la duración formateada en minutos o hh:mm
     */
    public function getDurationFormattedAttribute(): string
    {
        if (! $this->start_time || ! $this->end_time) {
            return 'En curso';
        }

        $minutes = (int) $this->start_time->diffInMinutes($this->end_time);
        if ($minutes < 60) {
            return "{$minutes} min";
        }

        $hours = floor($minutes / 60);
        $remainingMin = $minutes % 60;

        return "{$hours}h {$remainingMin}m";
    }
}
