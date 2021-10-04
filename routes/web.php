<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Models\Calendar;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

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
Route::group([
    'middleware' => 'AuthCheck',
], function () {
    Route::get('/', function () {
        return redirect('/home');
    });
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/home', function () {
        $mainCal = Calendar::where(['user_id' => Auth::id(), 'name' => "Main Calendar"])->first();
        return view('home', ['calendar' => $mainCal,'events' =>Event::where(['user_id' => Auth::id(), 'calendar_id' => $mainCal->id])->get()]);
    })->name('dashboard');
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
Route::group([
    'middleware' => 'AuthCheck',
], function () {
    //////////////////// ----------Authenticated user----------  ///////////////
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');  
    Route::patch('profile/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::patch('password/update/', [UserController::class, 'UpdatePassword'])->name('user.password.update');
    Route::patch('avatar/update', [UserController::class, 'UpdateAvatar'])->name('user.avatar.update');
    Route::delete('avatar/delete', [UserController::class, 'setDefaultAvatar'])->name('user.avatar.delete');    
    Route::delete('user/delete', [UserController::class, 'destroyAuthUser'])->name('user.delete');
    //////////////////// ----------Calendar module----------  ////////////////////
    Route::get('/calendars', [CalendarController::class, 'index'])->name('user.calendars');
    Route::get('/calendars/{id}', [CalendarController::class, 'show'])->name('user.calendars.show');
    Route::post('/calendars', [CalendarController::class, 'store'])->name('calendars.create');
    Route::delete('/calendars/{id}', [CalendarController::class, 'destroy'])->name('calendars.delete');

    //////////////////// ----------Events module----------  ////////////////////
    Route::get('/events', [EventController::class, 'index'])->name('user.events');
    Route::get('/calendars/{id}/events/create', [EventController::class, 'create'])->name('events.create.view');
    Route::get('/calendars/{cal_id}/events/edit/{ev_id}', [EventController::class, 'edit'])->name('events.edit.view');
    Route::post('/events', [EventController::class, 'store'])->name('events.create');
    Route::patch('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.delete');
});
