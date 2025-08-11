<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inventoryManage extends Model
{
    protected $fillable = [
        'store_id',
        'screenLocation_id',
        'spare_id',
        'model',
        'serial_number',
        'quantity',
        'remark',
        'status',
    ];

    public function getStatusAttribute($value)
    {
        return $value ? 'check-in' : 'check-out';
    }
}
