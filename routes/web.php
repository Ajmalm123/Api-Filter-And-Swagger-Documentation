<?php

use App\Http\Controllers\CustomMiddleWareController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/custom-mid', CustomMiddleWareController::class)
    ->middleware('custommiddleware');
