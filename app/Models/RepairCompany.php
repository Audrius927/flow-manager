<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    /**
     * Get all damage cases for this repair company.
     */
    public function damageCases(): HasMany
    {
        return $this->hasMany(DamageCase::class, 'repair_company_id');
    }
}
