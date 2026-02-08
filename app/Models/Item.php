<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'assets_id',
        'code',
        'status',
        'description',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assets_id');
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }
}
