<?php

use Illuminate\Support\Facades\Route;

// Vitamin Detection standalone page
Route::get('/vitamin-detection', function () {
    return view('vitamin-detection');
});

// Vitamin Scan Deep Learning + Gemini Page
Route::get('/vitamin-scan', function () {
    return view('vitamin-scan');
});

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');

