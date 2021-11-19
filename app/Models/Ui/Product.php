<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = [
        'product_name',
        'category_id',
        'image',
        'unitprice'
    ];

    public function category(){
        return $this->belognsTo(Category::class);
    }
}
