<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('instagram');
});

Route::get('/auth', [\App\Http\Controllers\InstagramAuthController::class, 'authenticate']);
Route::get('/instagram', [\App\Http\Controllers\InstagramController::class, 'getMedias'])->name('instagram');

