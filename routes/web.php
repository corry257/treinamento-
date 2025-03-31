<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstagiarioController;
use App\Http\Controllers\ExerciseController;

Route::get('/estagiarios', [EstagiarioController::class, 'index']);
Route::get('/estagiarios/create', [EstagiarioController::class, 'create']);

Route::get('/exercises/stats', [ExerciseController::class, 'stats'])->name('exercises.stats');
Route::get('/exercises/import', [ExerciseController::class, 'importCsv'])->name('exercises.import');