<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CounterController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 计数器路由
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/counters', [CounterController::class, 'index'])->name('counters.index');
    Route::post('/counters', [CounterController::class, 'store'])->name('counters.store');
    Route::put('/counters/{counter}', [CounterController::class, 'update'])->name('counters.update');
    Route::delete('/counters/{counter}', [CounterController::class, 'destroy'])->name('counters.destroy');
    Route::post('/counters/{counter}/increment', [CounterController::class, 'increment'])->name('counters.increment');
    Route::post('/counters/{counter}/decrement', [CounterController::class, 'decrement'])->name('counters.decrement');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
