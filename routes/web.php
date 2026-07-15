<?php

use Illuminate\Support\Facades\Route;

// GiziKu Landing Page
Route::get('/', function () {
    return view('welcome');
});

// GiziKu Screening
Route::get('/screening', function () {
    return view('giziku-screening');
});

// Legacy vitamin detection pages
Route::get('/vitamin-detection', function () {
    return view('vitamin-detection');
});

Route::get('/vitamin-scan', function () {
    return view('vitamin-scan');
});
