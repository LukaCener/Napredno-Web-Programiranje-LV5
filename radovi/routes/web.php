<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Language Switcher
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.update-role');
});

// Nastavnik (Teacher) Routes
Route::middleware(['auth', 'nastavnik'])->prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    Route::get('/applications', [TaskController::class, 'applications'])->name('tasks.applications');
    Route::patch('/applications/{application}/accept', [TaskController::class, 'acceptApplication'])->name('tasks.applications.accept');
    Route::patch('/applications/{application}/reject', [TaskController::class, 'rejectApplication'])->name('tasks.applications.reject');
});

// Student Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/student/tasks', [StudentController::class, 'index'])->name('student.tasks');
    Route::post('/student/tasks/{task}/apply', [StudentController::class, 'apply'])->name('student.apply');
});

require __DIR__.'/auth.php';