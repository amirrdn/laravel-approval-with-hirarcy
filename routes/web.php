<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('datatable.users');
    Route::get('/users/export', [UserController::class, 'export'])->name('export.users');
    Route::post('/users/import', [UserController::class, 'import'])->name('import.users');

    Route::resource('/users', UserController::class)
        ->middleware('permission:manage users');

    Route::resource('/roles', RoleController::class)
        ->middleware('permission:manage roles');

    Route::get('/projects/export', [ProjectController::class, 'export'])->name('export.projects');
    Route::get('/projects/datatable', [ProjectController::class, 'datatable'])->name('datatable.projects');
    Route::resource('/projects', ProjectController::class);

    Route::post('/projects/approval', [ProjectController::class, 'approval'])
        ->name('users.assign.form');
});

require __DIR__.'/auth.php';
