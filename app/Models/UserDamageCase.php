<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDamageCase extends Model
{
    protected $fillable = [
        'user_id',
        'damage_case_id',
    ];

    /**
     * Get the user assigned to this damage case.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the damage case for this assignment.
     */
    public function damageCase(): BelongsTo
    {
        return $this->belongsTo(DamageCase::class);
    }
}
