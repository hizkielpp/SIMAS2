<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



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
    public function kelolaAkun(){
        $role = DB::table('role')->select('kode','nama')->get();
        $akun = DB::table('users')->select('name','id','email','role')->get();
        return view('akun')->with('users',$akun)->with('role',$role);
    }
    public function inputAkun(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);
        $input = $request->except('_token');
        $input['password'] = Hash::make($input['password']);
        try{
            DB::table('users')->insert($request->except('_token'));
            return redirect()->route('kelolaAkun')->with('success','berhasil membuat data akun baru');
        }catch(\Exception $e)
        {
            return redirect()->route('kelolaAkun')->with('failed','gagal membuat data akun baru');
        }
    }
    public function getAkun(Request $request){
        $user = DB::table('users')->where('id',$request->id)->first();
        return response()->json($user);
    }
    public function deleteAkun(Request $request){
        $deleted = DB::table('users')->where('id', $request->input('idAkun'))->delete();
        if($deleted==0){
            return response('Data gagal dihapus', 406);
        }else{
            return response('Data berhasil dihapus',200);
        }
    }
    public function editAkun(Request $request){
        $request->validate([
            'name' => 'required',
            'role' => 'required'
        ]);
        $users = DB::table('users')->where('id',$request->input('idAkun'))->first();
        if($request->input('password') == null){
            $updatedValue = $request->except(['_token','idAkun','password']);
        }else{
            $updatedValue = $request->except(['_token','idAkun']);
        }
        try{
            DB::table('users')
            ->where('id',$request->input('idAkun'))  // find your surat by id
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update($updatedValue); 
            return redirect()->route('kelolaAkun')->with('success','berhasil mengubah data akun');
        }catch(\Exception $e)
        {
            return redirect()->route('kelolaAkun')->with('failed','gagal mengubah data akun');
        }
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