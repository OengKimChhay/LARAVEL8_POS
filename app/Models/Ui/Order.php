<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";

    public function cashier() {
        return $this->belongsTo(User::class);
    }


    public function details() {
        return $this->hasMany(OrderDetail::class);
    }
}
