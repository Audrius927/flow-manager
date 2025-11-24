<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageCasePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'damage_case_id',
        'disk',
        'path',
        'original_name',
    ];

    /**
     * Get the damage case that owns the photo.
     */
    public function damageCase(): BelongsTo
    {
        return $this->belongsTo(DamageCase::class);
    }
}

