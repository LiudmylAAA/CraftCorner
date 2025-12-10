<?php

use Illuminate\Support\Facades\Route;

Route::get('/main', function () {
    return view('main');
});
Route::get('/dashboard', function () {
    return view('main'); 
});