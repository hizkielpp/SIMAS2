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
    public function kelolaAkun()
    {
        $user = Auth::user();
        $role = DB::table('role')->select('kode', 'nama')->get();
        $akun = DB::table('users')->select('name', 'id', 'email', 'role')->get();
        return view('akun')->with(['users' => $akun, 'role' => $role, 'user' => $user]);
    }
    public function inputAkun(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            'NIP' => 'required'
        ]);
        $input = $request->except('_token');
        $input['password'] = Hash::make($input['password']);
        try {
            DB::table('users')->insert($request->except('_token'));
            return redirect()->route('kelolaAkun')->with('success', 'Data akun baru berhasil dibuat');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('kelolaAkun')->with('failed', 'Gagal membuat data akun baru');
        }
    }
    public function getAkun(Request $request)
    {
        $user = DB::table('users')->where('id', $request->id)->first();
        return response()->json($user);
    }
    public function deleteAkun(Request $request)
    {
        $deleted = DB::table('users')->where('id', $request->input('idAkun'))->delete();
        if ($deleted == 0) {
            return response('Data gagal dihapus', 406);
        } else {
            return response('Data berhasil dihapus', 200);
        }
    }
    public function editAkun(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required'
        ]);
        if ($request->input('password') == null) {
            $updatedValue = $request->except(['_token', 'idAkun', 'password']);
        } else {
            $updatedValue = $request->except(['_token', 'idAkun']);
        }
        try {
            DB::table('users')
                ->where('id', $request->input('idAkun'))  // find your surat by id
                ->limit(1)  // optional - to ensure only one record is updated.
                ->update($updatedValue);
            return redirect()->route('kelolaAkun')->with('success', 'Berhasil mengubah data akun');
        } catch (\Exception $e) {
            return redirect()->route('kelolaAkun')->with('failed', 'Gagal mengubah data akun');
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
        // $password = Hash::make($request->input('password'));
        // $login = DB::table('users')->where('NIP', $request->input('email'))->orWhere('email', $request->input('email'))->where('password', $password)->first();
        // if ($login) {
        //     // dd('success');
        //     $request->session()->put('user', $login);
        // }
        $credentials1 = ['email' => $request->input('email'), 'password' => $request->input('password')];
        $request->only('email', 'password');
        $credentials2 = ['NIP' => $request->input('email'), 'password' => $request->input('password')];
        if (Auth::attempt($credentials1) || Auth::attempt($credentials2)) {
            return redirect()->route('dashboard')->with('success', 'Signed in');
        }
        return redirect('login')->with('failed', 'Login details are not valid');
    }
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role == 2) {
            $jumlahSM = DB::table('suratmasuk')->where('created_by', $user->id)->count();
            $jumlahSK = DB::table('suratkeluar')->where('jenis', 'biasa')->where('status', 'digunakan')->where('created_by', $user->id)->count();
            $jumlahSA = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->where('created_by', $user->id)->count();
        } else {
            $jumlahSM = DB::table('suratmasuk')->count();
            $jumlahSK = DB::table('suratkeluar')->where('jenis', 'biasa')->where('status', 'digunakan')->count();
            $jumlahSA = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->count();
        }
        return view('index2')->with(['jumlahSM' => $jumlahSM, 'jumlahSK' => $jumlahSK, 'jumlahSA' => $jumlahSA, 'date' => now(), 'user' => $user]);
    }
    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
