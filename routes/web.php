<?php
use Illuminate\Support\Facades\Route;

Route::get('{any}', function () {
    return view('app'); // Change 'app' to the name of your SPA view file
})->where('any', '.*');