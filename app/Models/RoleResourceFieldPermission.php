<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleResourceFieldPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'resource',
        'field_name',
        'can_access',
        'can_view',
        'can_edit',
        'required',
    ];

    protected $casts = [
        'can_access' => 'boolean',
        'can_view' => 'boolean',
        'can_edit' => 'boolean',
        'required' => 'boolean',
    ];

    /**
     * Get the role that owns the permission.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}

