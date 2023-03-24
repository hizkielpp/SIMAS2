<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }
    public function index()
    {
        return view('login');
    }
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard')->with('success', 'Signed in');
        }
        return redirect('login')->with('Login details are not valid');
    }
    public function dashboard()
    {   
        $user = Auth::user();
        if($user->role==2){
            $jumlahSM = DB::table('suratmasuk')->where('created_by',$user->id)->count();
            $jumlahSK = DB::table('suratkeluar')->where('jenis','biasa')->where('status','digunakan')->where('created_by',$user->id)->count();
            $jumlahSA = DB::table('suratkeluar')->where('jenis','antidatir')->where('status','digunakan')->where('created_by',$user->id)->count();
        }else{
            $jumlahSM = DB::table('suratmasuk')->count();
            $jumlahSK = DB::table('suratkeluar')->where('jenis','biasa')->where('status','digunakan')->count();
            $jumlahSA = DB::table('suratkeluar')->where('jenis','antidatir')->where('status','digunakan')->count();
        }
        return view('index2')->with('jumlahSM',$jumlahSM)->with('jumlahSK',$jumlahSK)->with('jumlahSA',$jumlahSA)->with('date',now());

    }
    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
