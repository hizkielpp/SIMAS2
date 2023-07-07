<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


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
    public function kelolaAkun(Request $request)
    {
        $user = $request->session()->all();
        $role = DB::table('role')->select('kode', 'nama')->get();
        return view('akun')->with(['users' => User::all(), 'role' => $role, 'user' => $user]);
    }
    public function inputAkun(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required',
            'nip' => 'required',
            'email' => 'required',
            'role_id' => 'required',
            'password' => 'required'
        ]);
        $validatedData = $request->except('_token');

        // Enkripsi password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Input ke database
        try {
            DB::table('users')->insert($validatedData);
            return back()->with('success', 'Data akun baru berhasil dibuat');
        } catch (\Exception $e) {
            if (DB::table('users')->where('nip', $validatedData['nip'])) {
                return back()->with('nipFailed', 'NIP telah digunakan. Pastikan data yang anda masukkan benar!');
            }
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
        if ($request->input('password') == null) {
            $updatedValue = $request->except(['_token', 'idAkun', 'password', 'passwordConfirmation']);
        } else {
            $updatedValue = $request->except(['_token', 'idAkun', 'passwordConfirmation']);
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
        // $credentials = $request->validate([
        //     'email' => 'required|email:dns',
        //     'password' => 'required',
        // ]);
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        // $credentials1 = ['email' => $request->input('email'), 'password' => $request->input('password')];
        // $credentials2 = ['NIP' => $request->input('email'), 'password' => $request->input('password')];
        // $request->only('email', 'password');
        // if (Auth::attempt($credentials1) || Auth::attempt($credentials2)) {
        //     return redirect()->route('dashboard')->with('success', 'Signed in');
        // } else {
        //     return back()->with('loginFailed', 'Login gagal!');
        // }
        // if (Auth::attempt($credentials)) {
        //     return redirect()->route('dashboard')->with('success', 'Signed in');
        // }
        $credentials = User::where('nip', $request->email)
            ->orWhere('email', $request->email)
            ->first();
        if ($credentials) {
            if (Hash::check($request->password, $credentials->password)) {
                session(["User" => $credentials]);
                return redirect()->route('dashboard')->with('success', 'Signed in');
            } else {
                return back()->with('loginFailed', 'Login gagal!');
            }
        } else {
            return back()->with('loginFailed', 'Login gagal!');
        }
    }
    public function dashboard(Request $request)
    {
        $user = $request->session()->get('User');
        // dd($user->role->nama);
        // dd($user->role_id);
        if ($user->role_id == 2) {
            $jumlahSM = DB::table('suratmasuk')->where('created_by', $user->id)->count();
            $jumlahSK = DB::table('suratkeluar')->where('jenis', 'biasa')->where('status', 'digunakan')->where('created_by', $user->id)->count();
            $jumlahSA = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->where('created_by', $user->id)->count();
        } else {
            $jumlahSM = DB::table('suratmasuk')->count();
            $jumlahSK = DB::table('suratkeluar')->where('jenis', 'biasa')->where('status', 'digunakan')->count();
            $jumlahSA = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->count();
        }
        return view('index2')->with([
            'jumlahSM' => $jumlahSM,
            'jumlahSK' => $jumlahSK,
            'jumlahSA' => $jumlahSA,
            'date' => now(),
            'user' => $user,
        ]);
    }
    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
