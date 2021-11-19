<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $fillable = [
        'category_name',
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
