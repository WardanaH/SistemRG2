<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesignerController;

Route::middleware(['auth'])->group(function () {
    Route::get('/designers', [DesignerController::class, 'dashboard'])->name('designer.dashboard');
    Route::get('/designers/profile', function () {
        return view('designer.layout.profile');
    })->name('designer.profile');
});
