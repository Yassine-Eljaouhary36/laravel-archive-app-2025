<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\ControllerDashboardController;
use App\Http\Controllers\FileTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\TribunalController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('redirect.dashboard');

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->hasRole('controller')) {
        return redirect()->route('controller.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified', 'active'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Box routes
    Route::prefix('boxes')->group(function () {
        Route::get('/', [BoxController::class, 'index'])->name('boxes.index');
        Route::get('/create', [BoxController::class, 'create'])->middleware(['auth', 'role:admin|user'])->name('boxes.create');
        Route::post('/', [BoxController::class, 'store'])->middleware(['auth', 'role:admin|user'])->name('boxes.store');
        Route::get('/{box}', [BoxController::class, 'show'])->name('boxes.show');
        
        // Optional routes you might need later
        Route::get('/{box}/edit', [BoxController::class, 'edit'])->name('boxes.edit');
        Route::put('/{box}', [BoxController::class, 'update'])->name('boxes.update');
        // Route::delete('/{box}', [BoxController::class, 'destroy'])->name('boxes.destroy');
        
    });
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Users management
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');

    Route::get('/tribunaux', [TribunalController::class, 'index'])->name('admin.tribunaux.index');
    Route::post('/tribunaux/toggle-active', [TribunalController::class, 'toggleActive'])->name('admin.tribunaux.toggleActive');

    // File Types Routes
    Route::get('/file-types', [FileTypeController::class, 'index'])->name('admin.file-types.index');
    Route::post('/file-types/toggle-active', [FileTypeController::class, 'toggleActive'])->name('admin.file-types.toggleActive');
    Route::get('/file-types/create', [FileTypeController::class, 'create'])->name('admin.file-types.create');
    Route::post('/file-types', [FileTypeController::class, 'store'])->name('admin.file-types.store');
    Route::get('/file-types/{fileType}/edit', [FileTypeController::class, 'edit'])->name('admin.file-types.edit');
    Route::put('/file-types/{fileType}', [FileTypeController::class, 'update'])->name('admin.file-types.update');
    Route::delete('/file-types/{fileType}', [FileTypeController::class, 'destroy'])->name('admin.file-types.destroy');


    // Transferts Routes
    Route::get('/transferts', [TransfertController::class, 'index'])->name('admin.transferts.index');
    Route::get('/transferts/create', [TransfertController::class, 'create'])->name('admin.transferts.create');
    Route::get('/transferts/get-boxes', [TransfertController::class, 'getBoxes'])->name('admin.transferts.get-boxes');
    Route::post('/transferts', [TransfertController::class, 'store'])->name('admin.transferts.store');
    Route::get('/transferts/{transfert}', [TransfertController::class, 'show'])->name('admin.transferts.show');
    Route::get('/transferts/{transfert}/edit', [TransfertController::class, 'edit'])->name('admin.transferts.edit');
    Route::put('/transferts/{transfert}', [TransfertController::class, 'update'])->name('admin.transferts.update');

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/statistics', [StatisticController::class, 'index'])->name('admin.statistics.index');
    Route::get('/statistics/export', [StatisticController::class, 'exportPDF'])
    ->name('admin.statistics.export');

    Route::get('/latest-activities', [AdminDashboardController::class, 'latestActivities'])->name('admin.latestActivities');

});

// Controller routes
Route::prefix('controller')->middleware(['auth', 'role:controller'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('controller.users.index');
    // In routes/web.php
    Route::post('/boxes/{box}/validate', [BoxController::class, 'validateBox'])
        ->name('boxes.validate');
    Route::get('/dashboard', [ControllerDashboardController::class, 'index'])->name('controller.dashboard');
});

Route::get('/boxes/{box}/export', [BoxController::class, 'export'])
    ->middleware(['auth', 'role:admin|controller'])
    ->name('boxes.export');
Route::get('/boxes/{box}/label', [BoxController::class, 'generateBoxLabelPdf'])
    ->middleware(['auth', 'role:admin|controller'])
     ->name('boxes.label');


// Regular user dashboard (no prefix)
Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'active'])
    ->name('user.dashboard');
    
require __DIR__.'/auth.php';
