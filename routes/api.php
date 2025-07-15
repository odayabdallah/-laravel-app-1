<?php

use App\Http\Controllers\APIProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware('APIAuth')->group(function(){


    Route::controller(APIProductController::class)->group(function (){
    Route::get('products','index');
    Route::get('product/{id}','show');
    Route::post('product','store');
    Route::post('updateproduct/{id}','update');
    Route::delete('deleteproduct/{id}','delete');
});

});



Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

