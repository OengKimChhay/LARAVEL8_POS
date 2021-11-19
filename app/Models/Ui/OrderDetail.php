<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = "order_details";

    public function order() {  //M-1
        return $this->belongsTo(Order::class);
    }

    public function product() { //M-1
        return $this->belongsTo(Product::class);
    }
}
