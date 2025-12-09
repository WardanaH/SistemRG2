<?php

use App\Http\Controllers\SistemDokumen\MCompanyController;
use App\Http\Controllers\SistemDokumen\MProjectsController;
use App\Http\Controllers\SistemDokumen\MProjectTaskProgressController;
use App\Http\Controllers\SistemDokumen\MTasksController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return redirect()->route('companies.index');
// });

Route::middleware(['auth'])->group(function () {
    // Companies
    Route::get('/companies', [MCompanyController::class, 'index'])->name('companies.index');
    Route::post('/companies', [MCompanyController::class, 'store'])->name('companies.store');
    Route::get('/projects/{company}', [MCompanyController::class, 'show'])->name('companies.show');
    Route::put('/companies/{company}', [MCompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [MCompanyController::class, 'destroy'])->name('companies.destroy');

    //projects
    Route::post('/projects', [MProjectsController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [MProjectsController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [MProjectsController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/upload-proof', [MProjectsController::class, 'uploadProof'])
        ->name('projects.upload-proof');

    //task
    Route::get('/companies/{company}/tasks', [MTasksController::class, 'index'])->name('task.index');
    Route::post('/companies/{company}/tasks', [MTasksController::class, 'store'])->name('task.store');
    Route::put('/companies/{company}/tasks/{task}', [MTasksController::class, 'update'])->name('task.update');
    Route::delete('/companies/{company}/tasks/{task}', [MTasksController::class, 'destroy'])->name('task.destroy');

    //progress
    Route::get('/projects/{project}/progress', [MProjectTaskProgressController::class, 'progress'])->name('projects.progress');
    Route::post('/progress/{progress}/upload', [MProjectTaskProgressController::class, 'uploadBukti'])
        ->name('progress.upload');
    Route::post('/progress/{progress}/update-status', [MProjectTaskProgressController::class, 'updateStatus'])
        ->name('progress.update-status');
    Route::put('/progress/{progress}/update-note', [MProjectTaskProgressController::class, 'update'])->name('progress.note');
});
