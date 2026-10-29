<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
})->name('home');

// Resource rūteris izveido nosauktus maršrutus:
// products.index, products.create, products.store, products.show,
// products.edit, products.update, products.destroy
Route::resource('products', ProductController::class);
