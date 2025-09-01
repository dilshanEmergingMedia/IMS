<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventInventoryDetail extends Model
{
    protected $fillable = [
        'event_inventory_id',
        'asset_id',
        'model',
        'condition',
        'qty',
    ];

    public function eventInventory()
    {
        return $this->belongsTo(EventInventory::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
