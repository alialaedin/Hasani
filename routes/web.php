<?php

use App\Classes\Sms;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login-form');
	Route::post('/login', [AuthController::class, 'login'])->name('login');
	Route::redirect('/', 'login');
});


Route::middleware('auth:web')->group(function() {
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::post('/upload-file', [FileController::class, 'upload'])->name('upload-file');
	Route::get('/download-file/{file}', [FileController::class, 'download'])->name('download-file');
	Route::post('/send-file-sms/', [FileController::class, 'sms'])->name('send-sms');
	Route::post('/send-customer-sms/', [CustomerController::class, 'sms'])->name('send-customer-sms');
});

Route::get('/test', [TestController::class, 'run']);
