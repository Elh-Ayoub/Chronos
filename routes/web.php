<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SharingController;
use App\Models\Calendar;
use App\Models\Event;
use App\Models\Sharing;
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
    Route::get('/home', [CalendarController::class, 'home'])->name('dashboard');
    Route::get('/calendars', [CalendarController::class, 'index'])->name('user.calendars');
    Route::get('/calendars/{id}', [CalendarController::class, 'show'])->name('user.calendars.show');
    Route::patch('/calendars/{id}', [CalendarController::class, 'update'])->name('calendars.update');
    Route::post('/calendars', [CalendarController::class, 'store'])->name('calendars.create');
    Route::delete('/calendars/{id}', [CalendarController::class, 'destroy'])->name('calendars.delete');

    //////////////////// ----------Events module----------  ////////////////////
    Route::get('/events', [EventController::class, 'index'])->name('user.events');
    Route::get('/calendars/{id}/events/create', [EventController::class, 'create'])->name('events.create.view');
    Route::get('/calendars/{cal_id}/events/edit/{ev_id}', [EventController::class, 'edit'])->name('events.edit.view');
    Route::post('/events', [EventController::class, 'store'])->name('events.create');
    Route::patch('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.delete');

    //////////////////// ----------Share/invite module----------  ////////////////////
    //events
    Route::post('/events/{id}/invite', [SharingController::class, 'invite2event'])->name('events.invite');
    Route::get('/shared/{id}/accept', [SharingController::class, 'addSharedEvent'])->name('events.invite.add');
    Route::delete('/shared/{id}/delete', [SharingController::class, 'destroySharedEvent'])->name('events.invite.delete');
    //calendar
    Route::post('/calendar/{id}/share', [SharingController::class, 'shareCalendar'])->name('calendar.share');
    Route::get('/calendar/shared/{id}/accept', [SharingController::class, 'addSharedCalendar'])->name('calendar.invite.add');
    Route::patch('/calendar/shared/{cal_id}/update/{user_id}', [SharingController::class, 'updateRole'])->name('sharing.edit.role');
    Route::delete('/calendar/shared/{cal_id}/delete/{user_id}', [SharingController::class, 'destroyInvitedUser'])->name('sharing.delete.user');
    Route::delete('/calendar/shared/{cal_id}/cancel/{email}', [SharingController::class, 'destroyInvitation'])->name('sharing.cancel.user');

});
