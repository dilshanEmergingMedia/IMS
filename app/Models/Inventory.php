<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'screen_id',
    ];

    /**
     * Get the store that owns the inventory.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the screen that owns the inventory.
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(ScreenLocation::class);
    }

    /**
     * Get the details for the inventory.
     */
    public function inventoryDetails(): HasMany
    {
        return $this->hasMany(InventoryDetail::class);
    }
}
