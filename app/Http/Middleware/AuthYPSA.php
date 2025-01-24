<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthYPSA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check() && Auth::user()->role=='YPSA')
        {
            return $next($request);
            
        }else if(Auth::check() && Auth::user()->role=='ADMIN')
        {
            return $next($request);
            
        }else
        {
            return Redirect::back()->with('error', 'Anda Tidak Dapat Mengakses Menu Ini..');
        } 
        
        
    }
}
