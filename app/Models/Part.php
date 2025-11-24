<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number',
        'title',
        'part_category_id',
        'description',
        'price',
        'manufacturer',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the part category that owns the part.
     */
    public function partCategory(): BelongsTo
    {
        return $this->belongsTo(PartCategory::class, 'part_category_id');
    }

    /**
     * Get the storages for the part.
     */
    public function storages(): HasMany
    {
        return $this->hasMany(PartStorage::class);
    }
}

