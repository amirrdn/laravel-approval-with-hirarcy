<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('web')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'permission:view dashboard'])
  ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/roles', RoleController::class)
        ->middleware('permission:manage roles');

        Route::get('/users/datatable', [UserController::class, 'datatable'])->name('datatable.users');
        Route::get('/users/export', [UserController::class, 'export'])->name('export.users');
        Route::post('/users/import', [UserController::class, 'import'])->name('import.users');
    Route::resource('/users', UserController::class)
        ->middleware('permission:manage users');
    
    Route::get('/projects/export', [ProjectController::class, 'export'])->name('export.projects');
    Route::resource('/projects', ProjectController::class)->middleware('auth');
    Route::post('/projects/{id}', [ProjectController::class, 'approval'])
    ->name('users.assign.form')->middleware('auth');

});

require __DIR__.'/auth.php';
