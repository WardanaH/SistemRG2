<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperatorController;

Route::middleware(['auth'])->group(function () {
    Route::get('/operators', [OperatorController::class, 'dashboard'])->name('operator.dashboard');
    Route::get('/operators/profile', function () {
        return view('operator.layout.profile');
    })->name('operator.profile');
});
