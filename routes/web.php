<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstagiarioController;

Route::get('/estagiarios', [EstagiarioController::class,'index']);

Route::get('/estagiarios/create', [EstagiarioController::class,'create']);

