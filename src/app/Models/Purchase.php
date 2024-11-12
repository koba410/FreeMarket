<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        "item_id",
        "buyer_id",
        "delivary_postal_code",
        "delivary_address",
        "delivary_building",
        "payment_method",
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
