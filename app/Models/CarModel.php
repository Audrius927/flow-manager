<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarModel extends Model
{
    use HasFactory;

    protected $table = 'car_models';

    protected $fillable = [
        'title',
        'car_mark_id',
    ];

    /**
     * Get the mark that owns the model.
     */
    public function mark(): BelongsTo
    {
        return $this->belongsTo(CarMark::class, 'car_mark_id');
    }

    /**
     * Get the part storages for this model.
     */
    public function partStorages(): HasMany
    {
        return $this->hasMany(PartStorage::class, 'car_model_id');
    }
}
