<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('website');
});

Route::get('/dashboard', function () {
    return view('backend.student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('teacher')->group(function () {
   Route::get('register', [TeacherController::class,'ShowRegisterForm'])->name('teacher.register'); 
   Route::post('register/store', [TeacherController::class,'register'])->name('teacher.register.store');
   Route::get('dashboard' , [TeacherController::class,'dashboard'])->name('teacher.dashboard')->middleware(TeacherMiddleware::class);
   Route::get('login' , [TeacherController::class,'ShowLoginForm'])->name('teacher.login');
   Route::post('login/store' , [TeacherController::class,'login'])->name('teacher.login.store');
   Route::post('logut' , [TeacherController::class,'logout'])->name('teacher.logout');
});


Route::prefix('admin')->group(function () { 
    Route::get('login', [AdminController::class,'ShowLoginFrom'])->name('admin.login');
    Route::post('login/store', [AdminController::class,'login'])->name('admin.login.store');
    Route::get('dashboard', [AdminController::class,'dashboard'])->name(name: 'admin.dashboard')->middleware('admin');


});
require __DIR__.'/auth.php';
