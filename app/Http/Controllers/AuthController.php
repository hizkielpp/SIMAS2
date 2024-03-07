<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }
    public function kelolaAkun()
    {
        $user = session()->get('user');
        $role = DB::table('role')
            ->select('kode', 'nama')
            ->get();
        return view('akun')->with(['users' => User::all(), 'role' => $role, 'user' => $user]);
    }
    public function inputAkun(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate(
            [
                'name' => 'required',
                'nip' => 'required|unique:users,nip',
                'bagian' => 'required',
                'email' => 'required',
                'role_id' => 'required',
                'password' => 'required',
            ],
            [
                'nip.unique' => 'NIP telah digunakan. Silahkan gunakan NIP lain.',
            ],
        );
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
        $user = DB::table('users')
            ->where('nip', $request->id)
            ->first();
        return response()->json($user);
    }
    public function deleteAkun(Request $request)
    {
        $deleted = DB::table('users')
            ->where('nip', $request->input('idAkun'))
            ->delete();
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
            $updatedValue['password'] = Hash::make($updatedValue['password']);
            // return $updatedValue['password'];
        }
        try {
            DB::table('users')
                ->where('nip', $request->input('idAkun')) // find your surat by id
                ->limit(1) // optional - to ensure only one record is updated.
                ->update($updatedValue);
            return redirect()
                ->route('kelolaAkun')
                ->with('success', 'Berhasil mengubah data akun');
        } catch (\Exception $e) {
            return $e;
            return redirect()
                ->route('kelolaAkun')
                ->with('failed', 'Gagal mengubah data akun');
        }
    }
    public function index()
    {
        return view('login');
    }


    // Hash password import dari excel end
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials1 = ['email' => $request->input('email'), 'password' => $request->input('password')];
        $credentials2 = ['NIP' => $request->input('email'), 'password' => $request->input('password')];
        $request->only('email', 'password');
        $user = User::where('nip', $request->email)
            ->orWhere('email', $request->email)
            ->first();
        if (Auth::attempt($credentials1) || Auth::attempt($credentials2)) {
            session(['user' => $user]);
            $request->session()->regenerate();
            return redirect()
                ->route('dashboard.dashboard')
                ->with('success', 'Signed in');
        } else {
            return back()->with('loginFailed', 'Login gagal!');
        }
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
