<?php

use App\Http\Controllers\homecontroller;
use App\Http\Controllers\langController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('home',[homecontroller::class,'index']);
Route::get('chaneLang/{Lange}',[langController::class,'index']);


Route::middleware('isAdmin')->group(function(){
route::controller(ProductController::class)->group(function(){
    
    Route::get('products', 'index')->name('products');
Route::get('createproduct','create')->name('createproduct');
Route::post('product','store')->name('storeProduct');
Route::get('editproduct/{id}','edit')->name('edit');
Route::put('updateproduct/{id}','update')->name("updateproduct");
Route::delete('deleteproduct/{id}','delete')->name("deleteproduct");


});
});