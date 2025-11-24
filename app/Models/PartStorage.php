<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PartStorage extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'car_model_id',
        'engine_id',
        'fuel_type_id',
        'body_type_id',
        'storage_location',
        'quantity',
        'condition',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'received_at' => 'datetime',
    ];

    /**
     * Get the part that owns the storage.
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    /**
     * Get the car model associated with this storage.
     */
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    /**
     * Get the car mark through the car model.
     */
    public function carMark(): HasOneThrough
    {
        return $this->hasOneThrough(
            CarMark::class,
            CarModel::class,
            'id', // Foreign key on car_models table
            'id', // Foreign key on car_marks table
            'car_model_id', // Local key on part_storages table
            'car_mark_id' // Local key on car_models table
        );
    }

    /**
     * Get the engine associated with this storage.
     */
    public function engine(): BelongsTo
    {
        return $this->belongsTo(Engine::class);
    }

    /**
     * Get the fuel type associated with this storage.
     */
    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    /**
     * Get the body type associated with this storage.
     */
    public function bodyType(): BelongsTo
    {
        return $this->belongsTo(BodyType::class);
    }
}

