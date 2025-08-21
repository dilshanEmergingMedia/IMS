<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpareModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'spare_id',
        'remark',
        'status',
    ];

    public function spare()
    {
        return $this->belongsTo(Spare::class);
    }
}
