<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('/login')->with('success', 'Anda Berhasil Logout Maba ..');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email:dns'],
            'password' => ['required']
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (auth()->user()->role=='PMB')
            {
                return redirect()->intended('/operator/dashboard')->with('success', 'Hak Akses Sebagai Operator PMB ...');
            }else  if (auth()->user()->role=='YPSA')
            {
                return redirect()->intended('/keuangan/dashboard')->with('success', 'Hak Akses Sebagai Operator Keuangan ...');
            }else  if (auth()->user()->role=='ADMIN')
            {
                return redirect()->intended('/admin/dashboard')->with('success', 'Hak Akses Sebagai Admin ...');
            }else
            {
                return redirect('/login')->with('error', 'Hak Akses Tidak Dikenali..');   
            }
           
        }

        return redirect('/login')->with('error', 'Login Tidak Berhasil .. ');
    }
 

}
