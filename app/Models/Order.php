<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['table_number']; // Tambahkan atribut lain jika diperlukan

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}




