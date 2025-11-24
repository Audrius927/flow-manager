<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarMark extends Model
{
    use HasFactory;

    protected $table = 'car_marks';

    protected $fillable = [
        'title',
    ];

    /**
     * Get the models for the mark.
     */
    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'car_mark_id');
    }
}
