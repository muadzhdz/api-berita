<?php

use App\Http\Controllers\Api\BeritaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('berita', BeritaController::class);
