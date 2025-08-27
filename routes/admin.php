<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\EraController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\JiinController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('auth:admin')->group(function() {
    Route::get('/',         [HomeController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('/admins', AdminController::class)
        ->names([
            'index'     =>  'admin.index',
            'show'      =>  'admin.show',
            'create'    =>  'admin.create',
            'edit'      =>  'admin.edit',
            'update'    =>  'admin.update',
            'destroy'   =>  'admin.destroy',
            'store'     =>  'admin.store'
        ]);

    Route::resource('/eras', EraController::class)
        ->names([
            'index'     => 'era.index',
            'show'      => 'era.show',
            'create'    => 'era.create',
            'edit'      => 'era.edit',
            'update'    => 'era.update',
            'destroy'   => 'era.destroy',
            'store'     => 'era.store'
        ]);

    Route::get('jiins',                     [JiinController::class, 'index'     ])->name('jiin.index');
    Route::post('jiins',                    [JiinController::class, 'store'     ])->name('jiin.store');
    Route::get('jiins/create',              [JiinController::class, 'create'    ])->name('jiin.create');
    Route::get('jiins/{id}',                [JiinController::class, 'show'      ])->name('jiin.show');
    Route::get('jiins/{id}/edit',           [JiinController::class, 'edit'      ])->name('jiin.edit');
    Route::patch('jiins/{id}',              [JiinController::class, 'update'    ])->name('jiin.update');
    Route::delete('jiins/{id}',             [JiinController::class, 'destroy'   ])->name('jiin.destroy');

    Route::get('users',                     [UserController::class, 'index'     ])->name('user.index');
    Route::post('users',                    [UserController::class, 'store'     ])->name('user.store');
    Route::get('users/{id}',                [UserController::class, 'show'      ])->name('user.show');
    Route::get('users/{jiin_id}/create',    [UserController::class, 'create'    ])->name('user.create');
    Route::get('users/{id}/edit',           [UserController::class, 'edit'      ])->name('user.edit');
    Route::patch('users/{id}',              [UserController::class, 'update'    ])->name('user.update');
    Route::delete('users/{id}',             [UserController::class, 'destroy'   ])->name('user.destroy');
});