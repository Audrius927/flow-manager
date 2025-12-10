<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    /**
     * Get all damage cases for this insurance company.
     */
    public function damageCases(): HasMany
    {
        return $this->hasMany(DamageCase::class, 'insurance_company_id');
    }
}
