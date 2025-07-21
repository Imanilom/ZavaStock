<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AktivitasRiwayat;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    // Maximum number of login attempts
    protected $maxAttempts = 5;
    // Decay minutes for rate limiting
    protected $decayMinutes = 15;

    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->logActivity($user, 'login', 'User already logged in, redirected to dashboard');
            return redirect()->intended($this->redirectPath());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Rate limiter
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Gunakan guard 'web' untuk session login
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            $user = Auth::guard('web')->user();

            // Cek apakah akun aktif
            if (!$user->is_active) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.',
                ]);
            }

            // Simpan informasi login terakhir
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            $this->logActivity($user, 'login', 'User logged in successfully');

            // Alihkan ke halaman sesuai role
            return redirect($this->redirectPath());
        }

        // Jika gagal login
        $this->incrementLoginAttempts($request);
        $this->logFailedAttempt($request);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email', 'remember'));
    }


    public function user(Request $request)
    {
        $user = $request->user();
        $this->logActivity($user, 'profile', 'User accessed profile');
        return response()->json($user);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => config('auth.verify_email') ? null : now(),
            ]);

            event(new Registered($user));

            $token = $user->createToken('auth_token')->plainTextToken;

            $this->logActivity($user, 'register', 'User registered successfully');

            DB::commit();

            return response()->json([
                'message' => 'User registered successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            $this->logActivity($user, 'logout', 'User logged out');
            
            // Revoke all tokens
            $user->tokens()->delete();
            
            // Log last logout time
            $user->update([
                'last_logout_at' => now()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Anda telah logout.');
    }

    protected function redirectPath()
    {
        if (Auth::user()->role === 'admin') {
            return '/dashboard';
        }
        return '/dashboard';
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey($request), 
            $this->maxAttempts
        );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit(
            $this->throttleKey($request), 
            $this->decayMinutes * 60
        );
    }

    protected function clearLoginAttempts(Request $request)
    {
        RateLimiter::clear($this->throttleKey($request));
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }

    protected function logActivity($user, $activityType, $description)
    {
        AktivitasRiwayat::create([
            'user_id' => $user->id ?? null,
            'tipe_aktivitas' => $activityType,
            'subjek_tipe' => 'user',
            'subjek_id' => $user->id ?? null,
            'deskripsi' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function logFailedAttempt(Request $request)
    {
        AktivitasRiwayat::create([
            'tipe_aktivitas' => 'failed_login',
            'subjek_tipe' => 'user',
            'deskripsi' => 'Failed login attempt for email: '.$request->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}