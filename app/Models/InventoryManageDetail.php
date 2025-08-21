<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryManageDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_manage_id',
        'spare_id',
        'model',
        'quantity',
        'condition',
    ];

    public function inventoryManage()
    {
        return $this->belongsTo(InventoryManage::class);
    }

    public function spare()
    {
        return $this->belongsTo(Spare::class);
    }
}
