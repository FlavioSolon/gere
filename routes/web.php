<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// Painel do Escritório
Route::middleware(['auth', 'verified', 'office'])->group(function () {
    Route::get('/dashboard', [OfficeController::class, 'dashboard'])->name('dashboard');
    Route::post('/clients', [OfficeController::class, 'storeClient'])->name('office.clients.store');
    Route::get('/clients/{clientId}/report', [OfficeController::class, 'downloadReport'])->name('office.clients.report');
    Route::match(['get', 'post'], '/test-api', [OfficeController::class, 'testApi'])->name('office.test-api');
});

// Painel Admin
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/offices/create', [AdminController::class, 'createOffice'])->name('admin.offices.create');
    Route::post('/offices', [AdminController::class, 'storeOffice'])->name('admin.offices.store');
});

// Rotas de Perfil
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
