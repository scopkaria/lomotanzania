<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\WorkerLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Role-Based Login Routes
|--------------------------------------------------------------------------
*/

// Super Admin Login — /lomo-login
Route::middleware('guest')->group(function () {
    Route::get('lomo-login', [SuperAdminLoginController::class, 'create'])->name('super-admin.login');
    Route::post('lomo-login', [SuperAdminLoginController::class, 'store'])->name('super-admin.login.store');
});

// Admin Login — /login
Route::middleware('guest')->group(function () {
    Route::get('login', [AdminLoginController::class, 'create'])->name('login');
    Route::post('login', [AdminLoginController::class, 'store'])->name('admin.login.store');
});
Route::get('admin/login', fn () => redirect()->route('login'))->name('admin.login');

// Worker Login — /worker
Route::middleware('guest')->group(function () {
    Route::get('worker', [WorkerLoginController::class, 'create'])->name('worker.login');
    Route::post('worker', [WorkerLoginController::class, 'store'])->name('worker.login.store');
});

// Agent Login — handled in web.php (agent.login / agent.login.store)

/*
|--------------------------------------------------------------------------
| Forgot Password / Reset Password (shared)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (shared)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
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

    // Role-specific logout routes
    Route::post('super-admin/logout', [SuperAdminLoginController::class, 'destroy'])->name('super-admin.logout');
    Route::post('admin/logout', [AdminLoginController::class, 'destroy'])->name('admin.logout');
    Route::post('worker/logout', [WorkerLoginController::class, 'destroy'])->name('worker.logout');

    // Legacy logout — detect role and redirect appropriately
    Route::post('logout', function (\Illuminate\Http\Request $request) {
        $role = $request->user()?->role;
        \Illuminate\Support\Facades\Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return match ($role) {
            'super_admin' => redirect()->route('super-admin.login'),
            'admin'       => redirect()->route('login'),
            'worker'      => redirect()->route('worker.login'),
            'agent'       => redirect()->route('agent.login'),
            default       => redirect('/login'),
        };
    })->name('logout');
});
