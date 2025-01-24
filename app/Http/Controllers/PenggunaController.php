<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\UserSiuter;
use Illuminate\Http\Request;
use App\Helpers\AkademikHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function generatePin($length = 4)
    {
    $random = "";
    srand((double) microtime() * 1000000);
    $data = "123456123456789071234567890890";
    // $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; // if you need alphabatic also
    for ($i = 0; $i < $length; $i++) {
          $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
    }

    public function panitia(Request $request)
    {
        if($request->ajax()) {
            $getData=User::select(DB::raw('name, email,handphone,role,id,is_aktif'))->get();
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
                        'name'=>$item['name'],
                        'email'=>$item['email'],
                        'handphone'=>$item['handphone'],
                        'role'=> $item['role'], 
                        'is_aktif'=> $item['is_aktif']
                    ];
            }
        

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
            
        }
        
        
        return view('admin/pengguna/l_panitia');
    }

    public function storePanitia(Request $request)
    {
        try 
        {
            $gen_satu=$this->generatePin();
            $hp=$request->handphone;
            $pin=$gen_satu.''.substr($hp,-4);
            if ($request->id=='')
        {
            if ($request->role<>"FAKULTAS")
            {
                $request->validate([
                    'name'=>'required',
                    'email'=>'required',
                    'handphone' => 'required|unique:users|starts_with:8|min:10',
                    'role'=>'required',
                ]);

                User::Create([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'handphone'=>$request->handphone,
                    'role'=>$request->role,
                    'password'=>Hash::make($pin)
                ]);
            }else
            {
                $request->validate([
                    'name'=>'required',
                    'email'=>'required',
                    'handphone' => 'required|unique:users|starts_with:8|min:10',
                    'role'=>'required',
                    'is_aktif'=>'required'
                ]);

                User::Create([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'handphone'=>$request->handphone,
                    'role'=>$request->role,
                    'is_aktif'=>$request->is_aktif,
                    'password'=>Hash::make($pin)
                ]);
            }
            
            
            $message='Selamat, '.$request->name.' , Password Anda : '.$pin.'. Terima Kasih :) ';
        }else
        {
            if ($request->role<>"FAKULTAS")
            {
                $request->validate([
                    'id'=>'required',
                    'name'=>'required',
                    'email'=>'required',
                    'handphone' => 'required|starts_with:8|min:10',
                    'role'=>'required',
                ]);
                User::updateOrCreate(
                    ['id'=>$request->id],
                    [
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'handphone'=>$request->handphone,
                    'role'=>$request->role,
                ]);
            }else
            {
                $request->validate([
                    'id'=>'required',
                    'name'=>'required',
                    'email'=>'required',
                    'handphone' => 'required|starts_with:8|min:10',
                    'role'=>'required',
                    'is_aktif'=>'required',
                ]);
                User::updateOrCreate(
                    ['id'=>$request->id],
                    [
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'handphone'=>$request->handphone,
                    'role'=>$request->role,
                    'is_aktif'=>$request->is_aktif,
                ]);
            }
            
            $message='';
        }
            
           
            
            return response()->json(['status'=>'200','success'=>$message]);

        } catch (Exception $e) {
            return response()->json(['status'=>'201','error'=>$e->getMessage()]);
            
        }

    }

    

    public function tambahPanitia()
    {
        $data=new User();
        $fakultas=DB::table('pe3_fakultas')
        ->get();
        return view('admin/pengguna/f_panitia',[
            'data'=>$data,
            'fakultas'=>$fakultas
        ]);   
    }

    public function editPanitia($id)
    {
        $data=User::find($id);
        $fakultas=DB::table('pe3_fakultas')
        ->get();
        if (is_null($data))
        {
            $data=new User();
        }
        return view('admin/pengguna/f_panitia',[
            'data'=>$data,
            'fakultas'=>$fakultas
        ]);   
    }


    public function gantiPasswordPanitia(Request $request,$id)
    {
        try 
        {
            $data=User::find($id);
            $gen_satu=$this->generatePin();
            $hp=$data->handphone;
            $pin=$gen_satu.''.substr($hp,-4);
            
            User::updateOrCreate(
                    ['id'=>$request->id],
                    [
                    'password'=>Hash::make($pin)

                ]);
            
            
            $message='Password Untuk '.$request->name.' , sudah diganti ke '.$pin.' di Aplikasi Keuangan UNSAP YPSA. Silahkan Login di alamat : *https://online.ypsa-sumedang.or.id/petugas* menggunakan Email   dan Password tersebut Terima Kasih :) ';
        
            return response()->json(['success'=>$message]);

        } catch (Exception $e) {
            return redirect('/admin/panitia')->with('error', $e->getMessage());
            
        }

    }

    public function fakultas(Request $request)
    {
        if($request->ajax()) {
            $getData=UserSiuter::select(DB::raw('id,nidn,email,kodeprodi,role'))->get();
                $data=[];
                foreach ($getData as $item)
                {
                    $fak=AkademikHelpers::getFakultasNama($item['kodeprodi']);
                    $data[]=[
                        'id'=>$item['id'],
                        'nidn'=>$item['nidn'],
                        'email'=>$item['email'],
                        'fakultas'=>$fak,
                        'role'=> $item['role'], 
                    ];
            }
        

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
            
        }
        
        
        return view('admin/pengguna/l_fakultas');
    }

    public function storeFakultas(Request $request)
    {
        try 
        {
            if ($request->id=='')
        {
                $request->validate([
                    'nidn'=>'required',
                    'email'=>'required',
                    'kodeprodi'=>'required',
                ]);
                $id=Uuid::uuid4()->toString();
                UserSiuter::Create([
                    'id'=>$id,
                    'nidn'=>$request->nidn,
                    'email'=>$request->email,
                    'google_id'=>'1',
                    'role'=>'FAKULTAS',
                    'kodeprodi'=>$request->kodeprodi,
                ]);

            
            
            $message='';
        }else
        {
            $request->validate([
                'nidn'=>'required',
                'email'=>'required',
                'kodeprodi'=>'required',
            ]);
                UserSiuter::updateOrCreate(
                    ['id'=>$request->id],
                    [
                    'nidn'=>$request->nidn,
                    'email'=>$request->email,
                    'role'=>'FAKULTAS',
                    'kodeprodi'=>$request->kodeprodi,
                ]);
            
            $message='';
        }
            
            return response()->json(['status'=>'200','success'=>$message]);

        } catch (Exception $e) {
            return response()->json(['status'=>'201','error'=>$e->getMessage()]);
            
        }

    }

    

    public function hapusFakultas($id)
    {
        UserSiuter::find($id)->delete();
        return response()->json(['success'=>'Data Pengguna Fakultas dengan id='.$id.' Berhasil Dihapus !']);
    }

    public function tambahFakultas()
    {
        $data=new UserSiuter();
        $fakultas=DB::table('pe3_fakultas')
        ->get();
        return view('admin/pengguna/f_fakultas',[
            'data'=>$data,
            'fakultas'=>$fakultas
        ]);   
    }

    public function editFakultas($id)
    {
        $data=UserSiuter::find($id);
        $fakultas=DB::table('pe3_fakultas')
        ->get();
        if (is_null($data))
        {
            $data=new User();
        }
        return view('admin/pengguna/f_fakultas',[
            'data'=>$data,
            'fakultas'=>$fakultas
        ]);   
    }

}
