<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'spare_id',
        'model',
        'condition',
        'qty',
    ];

    /**
     * Get the inventory that owns the detail.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Get the spare that the detail refers to.
     */
    public function spare(): BelongsTo
    {
        return $this->belongsTo(Spare::class);
    }
}
