<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
    ];

    /**
     * Get the parent product.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    /**
     * Get child products.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    /**
     * Get all damage cases with this product.
     */
    public function damageCases(): HasMany
    {
        return $this->hasMany(DamageCase::class, 'product_id');
    }

    /**
     * Get all damage cases with this subproduct.
     */
    public function damageCasesAsSub(): HasMany
    {
        return $this->hasMany(DamageCase::class, 'product_sub_id');
    }
}
