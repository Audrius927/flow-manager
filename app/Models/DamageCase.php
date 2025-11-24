<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DamageCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_company',
        'product',
        'damage_number',
        'car_mark_id',
        'car_model_id',
        'license_plate',
        'first_name',
        'last_name',
        'phone',
        'order_date',
        'received_at',
        'received_location',
        'storage_location',
        'removed_from_storage_at',
        'returned_to_storage_at',
        'returned_to_client_at',
        'repair_company',
        'planned_repair_start',
        'planned_repair_end',
        'finished_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'received_at' => 'datetime',
        'removed_from_storage_at' => 'date',
        'returned_to_storage_at' => 'date',
        'returned_to_client_at' => 'date',
        'planned_repair_start' => 'date',
        'planned_repair_end' => 'date',
        'finished_at' => 'date',
    ];

    /**
     * Get the users assigned to this damage case.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_damage_cases')
            ->withTimestamps();
    }

    /**
     * Get the car mark associated with this damage case.
     */
    public function carMark(): BelongsTo
    {
        return $this->belongsTo(CarMark::class, 'car_mark_id');
    }

    /**
     * Get the car model associated with this damage case.
     */
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    /**
     * Get all documents attached to the damage case.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(DamageCaseDocument::class);
    }

    /**
     * Get all photos attached to the damage case.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(DamageCasePhoto::class);
    }
}

