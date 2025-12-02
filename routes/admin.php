<?php

use App\Http\Controllers\Admin\BusinessSettingController;
use App\Http\Controllers\Admin\ClassesController;
use App\Http\Controllers\Admin\CodesController;
use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\Admin\LessonsController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers\Admin')->group(function () {
    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
});

Route::middleware(['admin.auth:admin'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->as('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');
        Route::post('/updatePersonal', [ProfileController::class, 'updatePersonal'])->name('updatePersonal');
    });

    Route::prefix('users')->as('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('status/{id}', [UserController::class, 'status'])->name('status');
    });

    Route::prefix('students')->as('students.')->group(function () {
        Route::get('/', [StudentsController::class, 'index'])->name('index');
        Route::get('/editCode/{id}', [StudentsController::class, 'editCode'])->name('editCode');
        Route::post('/updateCode', [StudentsController::class, 'updateCode'])->name('updateCode');
        Route::get('/{id}', [StudentsController::class, 'show'])->name('show');
    });

    Route::prefix('codes')->as('codes.')->group(function () {
        Route::get('/', [CodesController::class, 'index'])->name('index');
        Route::get('/codes_export', [CodesController::class, 'codes_export'])->name('codes_export');
        Route::delete('/{id}', [CodesController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('classes')->as('classes.')->controller(ClassesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('courses')->as('courses.')->controller(CoursesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('sales')->as('sales.')->controller(SalesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('subjects')->as('subjects.')->controller(SubjectController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('lessons')->as('lessons.')->controller(LessonsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/{id}/copy', 'copy')->name('copy');
        Route::post('/{id}/copy', 'store_copy')->name('store_copy');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::post('/{id}/edit', 'update')->name('update');
        Route::get('/{id}', 'show')->name('show');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('business-setting')->as('business-setting.')->group(function () {
        Route::post('/general-update', [BusinessSettingController::class, 'general_update'])->name('general-update');
        Route::post('/mail-config', [BusinessSettingController::class, 'mail_config'])->name('mail-config');
        Route::post('/recaptcha-update', [BusinessSettingController::class, 'recaptcha_update'])->name('recaptcha-update');

        Route::get('/{tab?}', [BusinessSettingController::class, 'preview'])->name('views');
    });
});
