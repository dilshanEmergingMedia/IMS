<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventInventory extends Model
{
    protected $fillable = [
        'store_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function inventoryDetails()
    {
        return $this->hasMany(EventInventoryDetail::class);
    }
}
