<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 //////////////////// ----------Authentication module----------  ////////////////////
 Route::get('/home', function () {
    return view('home');
})->name('dashboard');
Route::group([
    'middleware' => 'AuthCheck',
], function () {
    Route::get('/', function () {
        return redirect('dashboard');
    });
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
});
Route::group([
    'middleware' => 'web',
], function () {
    //  ---------Email verification----------
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
    Route::post('/email/verify/resend', [VerifyEmailController::class, 'resendVerification'])->name('verification.send');
    Route::get('/email/verify', function(){
        return view('Auth.verifyEmail');
    })->name('verification.resend');
    Route::get('/email/verify/success', function(){
        return redirect('auth/login')->with('success', 'Email verified successfully!');
    });
    Route::get('/email/verify/already-success', function(){
        return redirect('auth/login')->with('success', 'Email already verified! Thank you.');
    });
    // -------password reset --------------
    Route::get('auth/login', function(){
        return view('Auth.login');
    })->name('login');
    Route::get('auth/register', function(){
        return view('Auth.register');
    })->name('register');
    Route::get('auth/forgot-password', function(){
        return view('Auth.forgot-password');
    })->name('password.forgot');
    Route::get('/reset-password/{token}/{email}', function (Request $request, $token, $email) {
        return view('Auth.reset-password', ['token' => $token, 'email' => $email]);
    })->middleware('guest')->name('password.reset');
    Route::patch('auth/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');
    Route::post('auth/forgot-password',[AuthController::class, 'sendResetLink'])->middleware('guest')->name('password.send');
    
});