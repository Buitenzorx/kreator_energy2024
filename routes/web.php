<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;

Route::get('/', [SampahController::class, 'index']);
Route::get('/history', [SampahController::class, 'history']);
Route::get('/api/tong-sampah-data', [SampahController::class, 'fetchLatestData']);
// Route::get('/', function () {
//     return view('welcome');
// });
