<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $fillable = [
        'code',
        'user_name',
        'checkout_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'checkout_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
