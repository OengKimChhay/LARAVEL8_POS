<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Auth\User;
use App\Models\Ui\Product;
use App\Models\Ui\Category;
use App\Models\Ui\Table;

class DashboardController extends Controller
{
    public function allProduct(){
        $product = Product::orderBy('product_name','ASC')->get();
        return response()->json($product);
    }

    public function allUser(){
        $user = User::orderBy('id','ASC')->get();
        return response()->json($user);
    }

    public function allCategory(){
        $category = Category::orderBy('category_name','ASC')->get();
        return response()->json($category);
    }

    public function allTable()
    {
        $table = Table::orderBy('name','ASC')->get();
        return response()->json($table);
    }
}
