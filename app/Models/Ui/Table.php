<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $table = "tables";
    protected $fillable = [
        'name'
    ];
}
