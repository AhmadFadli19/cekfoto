<?php

use Illuminate\Support\Facades\Route;

// Vitamin Detection standalone page
Route::get('/vitamin-detection', function () {
    return view('vitamin-detection');
});

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');

