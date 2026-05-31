<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable; // Importe o Fillable

#[Table('orders')]
#[Fillable([
    'product_name',
    'tracking_code',
    'origin_address',
    'destination_address',
    'latitude_origem',
    'longitude_origem',
    'latitude_destino',
    'longitude_destino'
])] 
class Order extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
}