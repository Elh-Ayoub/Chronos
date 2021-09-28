<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Calendar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Mail;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/home');
        }
        return back()->with('fail', 'The provided credentials do not match our records.');
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users|between:5,30',
            'full_name' => 'required|string|between:5,30',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        if($validator->fails()){
            return back()->with('fail', json_decode($validator->errors()->toJson()));
        }
        $name = substr($request->input('username'), 0, 2);
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
            'profile_photo' => 'https://ui-avatars.com//api//?name='.$name.'&color=7F9CF5&background=EBF4FF',
            ]
        ));
        event(new Registered($user));
        if($user){
            Calendar::create([
                'name' => 'Main Calendar',
                'description' => "This is the main and default calendar.",
                "user_id" => $user->id,
            ]);
            return back()->with('success', 'Account created successfully. Please check mailbox to verify email.');
        }else{
            return back()->with('fail', ['error' => 'Somthing went wrong! Try again.']);
        }
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/auth/login');
    }
    public function useProfile() {
        $data = ['loggedUserInfo' => Auth::user()];
        return view('Admin.profile', $data);
    }
    public function dashboard(){
        return view('home');
    }
    public function mailUser($user, $sm) {
        $data = array('username'=> $user->username,
          'full_name'=> $user->full_name,
          'email' => $user->email,
          'social_media' => $sm,
        ); 
        Mail::send('mailUser',$data, function($message ) use($data) {
           $message->to($data['email'], 'Testing Point')->subject
              ('Registration Email');
           $message->from('ayoub.el-haddadi@gmail.com','WantOrder');
        });
    }
    function sendResetLink(Request $request){
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['success' => __($status)])
                    : back()->with(['fail' => __($status)]);
    }
    function resetPassword(Request $request){
        $request->validate([
            'token',
            'email' => 'email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->with(['email' => [__($status)]]);
    }
}
