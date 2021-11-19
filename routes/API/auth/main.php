<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;

Route::post('/login',[UserController::class, 'Login'])->name('admin.login');
Route::post('/register',[UserController::class, 'Register'])->name('admin.register');
Route::group(['prefix'=>'auth','middleware'=>'auth:sanctum'],function(){
    Route::get('/GetUserData',[UserController::class, 'GetUserData'])->name('admin.get');
    Route::put('/update/{id}',[UserController::class, 'Update'])->name('admin.update');
    Route::delete('/delete/{id}',[UserController::class, 'Delete'])->name('admin.delete');
    Route::post('/logout',[UserController::class, 'Logout'])->name('admin.logout');
});
// we use laravel sanctum to generate token string
// to protect backend routes by auth:sanctum

