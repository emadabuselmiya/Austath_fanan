<?php

use App\Http\Controllers\Admin\BusinessSettingController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\RawMaterialController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StocksController;
use App\Http\Controllers\Admin\TrafficsController;
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

    Route::prefix('business-setting')->as('business-setting.')->group(function () {
        Route::post('/general-update', [BusinessSettingController::class, 'general_update'])->name('general-update');
        Route::post('/mail-config', [BusinessSettingController::class, 'mail_config'])->name('mail-config');
        Route::post('/recaptcha-update', [BusinessSettingController::class, 'recaptcha_update'])->name('recaptcha-update');

        Route::get('/{tab?}', [BusinessSettingController::class, 'preview'])->name('views');
    });
});
