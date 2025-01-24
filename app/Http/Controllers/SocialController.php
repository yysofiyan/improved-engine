<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserSiuter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();
            $getUser=UserSiuter::select(DB::raw('*'))
            ->where('email',$user->email);
            if ($getUser->count()>0)
            {
                $data=$getUser->first();
                $prodi = DB::table('pe3_prodi')
                        ->where('kode_fakultas',$data['kodeprodi'])
                        ->get();
                        $l_prodi=[];
                        foreach ($prodi as $prodis)
                        {
                            $l_prodi[]=$prodis->config;
                        }
                        
                $request->session()->put([
                    'nidn' => $data['nidn'],
                    'email' => $data['email'],
                    'kodeprodi' => $data['kodeprodi'],
                    'l_prodi' => $l_prodi,
                    'role'=>$data['role'],
                ]);
                return redirect('fakultas/dashboard')->with('success', 'Login Berhasil, Silahkan pilih menu :');
            }else
            {
                return redirect('/login-fakultas')->with('error', 'User Google Mail Tidak Terdaftar ..');
            }




        } catch (Exception $e) {
            return redirect('/login-fakultas')->with('error', 'Terdapat Kesalahan !!');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login-fakultas');
    }

}
