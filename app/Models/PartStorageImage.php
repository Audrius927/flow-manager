<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartStorageImage extends Model
{
    use HasFactory;

    protected $table = 'part_storages_images';
    protected $fillable = [
        'part_storage_id',
        'disk',
        'path',
        'original_name',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the part storage that owns the image.
     */
    public function partStorage(): BelongsTo
    {
        return $this->belongsTo(PartStorage::class);
    }
}
