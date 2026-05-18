<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use LogsActivity;
    
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);
            
            // Cari user dengan BINARY (case-sensitive)
            $user = User::whereRaw('BINARY username = ?', [$request->username])->first();
            
            if ($user && Hash::check($request->password, $user->password)) {
                // Cek apakah user aktif
                if (isset($user->is_active) && !$user->is_active) {
                    Log::warning('Login attempt by inactive user', [
                        'username' => $request->username,
                        'user_id' => $user->id,
                        'ip' => $request->ip()
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun Anda tidak aktif. Silahkan hubungi administrator.'
                    ], 403);
                }
                
                // Regenerate session untuk keamanan
                $request->session()->regenerate();
                
                // Login menggunakan guard default
                Auth::login($user);
                
                // Log aktivitas login sukses
                $this->logActivity(
                    'LOGIN',
                    'AUTHENTICATION',
                    "User {$user->name} ({$user->role}) berhasil login" . ($user->cabang ? " - Cabang: {$user->cabang}" : ""),
                    null,
                    [
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'login_time' => now()->toDateTimeString()
                    ]
                );
                
                Log::info('User logged in successfully', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'role' => $user->role,
                        'cabang' => $user->cabang,
                        'cabang_binaan' => $user->cabang_binaan,
                        'level' => $user->level,
                        'is_active' => $user->is_active ?? true
                    ],
                    'message' => 'Login berhasil'
                ]);
            }
            
            // Log percobaan login gagal
            Log::warning('Failed login attempt', [
                'username' => $request->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'attempt_time' => now()->toDateTimeString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in login: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log aktivitas logout
            if ($user) {
                $this->logActivity(
                    'LOGOUT',
                    'AUTHENTICATION',
                    "User {$user->name} ({$user->role}) berhasil logout" . ($user->cabang ? " - Cabang: {$user->cabang}" : ""),
                    null,
                    [
                        'ip_address' => $request->ip(),
                        'logout_time' => now()->toDateTimeString()
                    ]
                );
                
                Log::info('User logged out', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'ip' => $request->ip()
                ]);
            }
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in logout: ' . $e->getMessage());
            
            // Tetap coba logout meskipun ada error
            try {
                Auth::logout();
                $request->session()->invalidate();
            } catch (\Exception $inner) {
                // Ignore inner exception
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        }
    }
    
    public function checkAuth(Request $request)
    {
        try {
            // Cek apakah user terautentikasi
            if (Auth::check()) {
                $user = Auth::user();
                
                // Refresh user data dari database
                $user = User::find($user->id);
                
                if (!$user) {
                    Auth::logout();
                    return response()->json([
                        'authenticated' => false,
                        'message' => 'User tidak ditemukan'
                    ]);
                }
                
                // Cek apakah user masih aktif
                if (isset($user->is_active) && !$user->is_active) {
                    Auth::logout();
                    return response()->json([
                        'authenticated' => false,
                        'message' => 'Akun Anda telah dinonaktifkan'
                    ]);
                }
                
                return response()->json([
                    'authenticated' => true,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'role' => $user->role,
                        'cabang' => $user->cabang,
                        'cabang_binaan' => $user->cabang_binaan,
                        'level' => $user->level,
                        'is_active' => $user->is_active ?? true,
                        'role_label' => $user->role_label ?? $user->role,
                        'display_name' => $user->display_name ?? $user->name
                    ]
                ]);
            }
            
            return response()->json([
                'authenticated' => false
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in checkAuth: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'authenticated' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Verify password for sensitive operations (delete, etc)
     */
    public function verifyPassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string'
            ]);
            
            $user = Auth::user();
            
            if (!$user) {
                Log::warning('Password verification attempt without authentication', [
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }
            
            if (Hash::check($request->password, $user->password)) {
                Log::info('Password verified successfully', [
                    'user_id' => $user->id,
                    'username' => $user->username
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Password valid'
                ]);
            }
            
            Log::warning('Password verification failed', [
                'user_id' => $user->id,
                'username' => $user->username,
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in verifyPassword: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get current authenticated user profile
     */
    public function me()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
            
            $user = Auth::user();
            $user = User::find($user->id); // Refresh data
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                    'cabang' => $user->cabang,
                    'cabang_binaan' => $user->cabang_binaan,
                    'level' => $user->level,
                    'is_active' => $user->is_active ?? true,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in me: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Refresh user session/token
     */
    public function refresh(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
            
            // Regenerate session to prevent fixation
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Log session refresh
            $this->logActivity(
                'REFRESH_SESSION',
                'AUTHENTICATION',
                "Session direfresh untuk user {$user->name} ({$user->role})",
                null,
                ['ip' => $request->ip(), 'refresh_time' => now()->toDateTimeString()]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Session refreshed',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                    'cabang' => $user->cabang
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in refresh: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Change password from within auth controller
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:4|different:current_password',
                'confirm_password' => 'required|string|same:new_password'
            ]);
            
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }
            
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                Log::warning('Failed password change attempt - wrong current password', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini salah'
                ], 422);
            }
            
            $oldData = ['password' => '********'];
            
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            // Log password change
            $this->logActivity(
                'CHANGE_PASSWORD',
                'AUTHENTICATION',
                "User {$user->name} ({$user->role}) mengubah password sendiri",
                $oldData,
                ['password' => '********', 'changed_at' => now()->toDateTimeString()]
            );
            
            Log::info('Password changed successfully', [
                'user_id' => $user->id,
                'username' => $user->username,
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in changePassword: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get login history for current user (optional)
     */
    public function loginHistory(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
            
            // Ambil history login dari activity log jika tabel ada
            if (class_exists(\App\Models\ActivityLog::class)) {
                $loginHistory = \App\Models\ActivityLog::where('user_id', $user->id)
                    ->where('action', 'LOGIN')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(['created_at', 'ip_address', 'user_agent']);
            } else {
                $loginHistory = collect();
            }
            
            return response()->json([
                'success' => true,
                'data' => $loginHistory
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in loginHistory: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat history login'
            ], 500);
        }
    }
}