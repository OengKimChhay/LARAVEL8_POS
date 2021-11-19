<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

    // for auth route
    require(__DIR__.'/API/auth/main.php');
    // for ui route
    require(__DIR__.'/API/ui/main.php');


    /* Nte:: if you want ot change directory you can check in RouteServiceProvider */