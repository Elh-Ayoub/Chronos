<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::user() && ($request->path() != 'auth/login' && $request->path() != 'auth/register')){
            return redirect('auth/login')->with('fail', 'You must be logged in first.');
        }
        if(Auth::user() && ($request->path() == 'auth/login' || $request->path() == 'auth/register')){
            return redirect('/');
        }
        return $next($request);
    }
}
