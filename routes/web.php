<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});


Route::any('/test', [TestController::class, 'index'])->name('test.index');

Route::any('/test/search', [TestController::class, 'search'])->name('test.search');
