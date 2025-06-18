<?php

use App\Http\Controllers\Game\CrimeController;
use App\Http\Controllers\Game\DashboardController;
use App\Http\Controllers\Game\InventoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('game')->name('game.')->group(function () {

        Route::get('/crimes', [CrimeController::class, 'index'])->name('crimes.index');
        Route::post('/crimes/{crime}/attempt', [CrimeController::class, 'attempt'])->name('crimes.attempt');

        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    });
});

require __DIR__.'/auth.php';
