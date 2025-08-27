<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class inventoryManage extends Model
{
    protected $fillable = [
        'store_id',
        'screen',
        'screenLocation_id',
        'model',
        'asset_id',
        'asset_models_id',
        'serial_number',
        'quantity',
        'remark',
        'status',
    ];

    public function getStatusAttribute($value)
    {
        return $value ? 'check-in' : 'check-out';
    }

    public function inventoryManageDetails(): HasMany
    {
        return $this->hasMany(InventoryManageDetail::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function screenLocation()
    {
        return $this->belongsTo(screenLocation::class, 'screenLocation_id');
    }
    public function spare()
    {
        return $this->belongsTo(Spare::class);
    }
}
