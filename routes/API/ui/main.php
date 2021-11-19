<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ui\CategoryController;
use App\Http\Controllers\Ui\ProductController;
use App\Http\Controllers\Ui\GetAllProductController;
use App\Http\Controllers\Ui\TableController;
use App\Http\Controllers\Ui\DashboardController;
use App\Http\Controllers\Ui\POSController;
use App\Http\Controllers\Ui\OrderController;


Route::group(['prefix'=>'ui','middleware'=>'auth:sanctum'],function(){
    // for categories
    Route::resource('/category',CategoryController::class);
    // for products
    Route::resource('/product',ProductController::class);
    // for table
    Route::resource('/table',TableController::class);
    
    // for dashboard( allproduct,allcat,alluser,alltable)
    Route::get('/allproduct',[DashboardController::class,'allProduct']);
    Route::get('/alluser',[DashboardController::class,'allUser']);
    Route::get('/allcategory',[DashboardController::class,'allCategory']);
    Route::get('/alltable',[DashboardController::class,'allTable']);
    
    // for make order
    Route::group(['prefix'=>'pos'],function(){
        // for POS
        Route::post('/',[POSController::class,'takeOrder']);
        // for listing order
        Route::get('/listing',[OrderController::class,'listing']);
        // for delete

        Route::delete('/delete/{id}',[OrderController::class,'delete']);
    });
});