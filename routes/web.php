<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\ExerciseController;

// Landing
Route::get('/', [PackController::class, 'home'])->name('landing');

// Packs
Route::get('/packs', [PackController::class, 'index'])->name('packs.index'); // Lista todos los packs
Route::post('/packs', [PackController::class, 'store'])->name('packs.store'); // Crear pack
Route::post('/packs/{pack}/activate', [PackController::class, 'activate'])->name('packs.activate'); // Activar pack

// Días (usando DayController con virtual weeks)
Route::get('/packs/{pack}/days', [PackController::class, 'showDays'])->name('packs.days');
Route::get('/packs/{pack}/day/{day}', [ExerciseController::class, 'index'])->name('packs.day.show'); // Ver ejercicios de un día

// Ejercicios
Route::post('/packs/{pack_id}/day/{day_id}/exercise', [ExerciseController::class, 'store'])->name('exercise.store'); // Añadir ejercicio
Route::post('/exercise/{id}/complete', [ExerciseController::class, 'markComplete'])->name('exercise.complete'); // Marcar como completado
Route::post('/exercise/{id}/update', [ExerciseController::class, 'updateMetrics'])->name('exercise.update'); // Actualizar métricas
Route::post('/exercise/{exercise}/hiit', [ExerciseController::class, 'addHiitExercise'])->name('exercise.hiit.add'); // Añadir sub-ejercicio HIIT
// HIIT
Route::post('/exercise/{id}/hiit/add', [ExerciseController::class, 'addHiitExercise'])->name('exercise.hiit.add');
Route::delete('/exercise/{id}/delete', [ExerciseController::class, 'destroy'])->name('exercise.destroy');
