<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;

class AuthController extends Controller
{
    public function index()
    {
        // if (auth()->check()) {
        //     return redirect('/apps');
        // }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate(); // penting untuk keamanan sesi baru
                // echo "Login berhasil!";
                return redirect()->intended('/apps');
            }
            // echo "Login gagal!";
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            echo "Terjadi kesalahan: " . $th->getMessage();
        }
        

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
