<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    
    public function showLoginForm()
    {
        // Cek apakah user sudah login
        if (Auth::check()) {
            return redirect()->intended('/welcome'); 
        }

        return view('auth.login'); // Tampilkan form login
    }
    
    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }   

    /**
     * Register a new user.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);     

        $saveUser = new \App\Models\User();
        $saveUser->name = $request->name;
        $saveUser->email = $request->email;
        $saveUser->password = Hash::make($request->password);
        $saveUser->role = 'user'; // Default role
        $saveUser->save();  

        // Buat token untuk user yang baru dibuat
        $token = $saveUser->createToken('auth_token')->plainTextToken;  

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $saveUser
        ], 201);
    }

    /**
     * Logout user and delete tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
        $token = $request->user()->currentAccessToken();

        // Hapus token jika ada
        if ($token) {
            $token->delete();
        }
    }
        Auth::logout(); // Logout user dari sesi

        return redirect('/login')->with('message', 'Anda telah logout.');
    }

}