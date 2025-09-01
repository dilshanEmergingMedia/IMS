<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset_id',
        'remark',
        'status',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
