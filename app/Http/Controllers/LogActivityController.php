<?php
// app/Http/Controllers/LogActivityController.php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            if (!in_array($user->role, ['admin', 'manager'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied - Hanya admin dan manager yang dapat melihat log aktivitas'
                ], 403);
            }
            
            $query = LogActivity::query();
            
            // Filter berdasarkan role user (manager hanya lihat cabangnya)
            if ($user->role === 'manager') {
                $query->where('user_cabang', $user->cabang);
            }
            
            // Filter pencarian umum
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('user_name', 'like', "%{$search}%")
                      ->orWhere('action', 'like', "%{$search}%")
                      ->orWhere('module', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%");
                });
            }
            
            // Filter module (termasuk LOGIN, LOGIN_FAILED, AUTHENTICATION)
            if ($request->has('module') && $request->module) {
                if ($request->module === 'AUTHENTICATION') {
                    $query->where('module', 'AUTHENTICATION');
                } else {
                    $query->where('module', $request->module);
                }
            }
            
            // Filter action (LOGIN, LOGOUT, LOGIN_FAILED, etc)
            if ($request->has('action') && $request->action) {
                $query->where('action', $request->action);
            }
            
            // Filter spesifik untuk login/logout activities
            if ($request->has('auth_type')) {
                switch ($request->auth_type) {
                    case 'login_success':
                        $query->where('action', 'LOGIN')->where('module', 'AUTHENTICATION');
                        break;
                    case 'login_failed':
                        $query->where('action', 'LOGIN_FAILED');
                        break;
                    case 'logout':
                        $query->where('action', 'LOGOUT');
                        break;
                    case 'password_change':
                        $query->where('action', 'CHANGE_PASSWORD');
                        break;
                    case 'session_refresh':
                        $query->where('action', 'REFRESH_SESSION');
                        break;
                }
            }
            
            // Filter berdasarkan user (nama user tertentu)
            if ($request->has('user_name') && $request->user_name) {
                $query->where('user_name', 'like', "%{$request->user_name}%");
            }
            
            // Filter berdasarkan role user
            if ($request->has('user_role') && $request->user_role) {
                $query->where('user_role', $request->user_role);
            }
            
            // Filter tanggal
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            // Filter tanggal spesifik
            if ($request->has('date') && $request->date) {
                $query->whereDate('created_at', $request->date);
            }
            
            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);
            
            $limit = $request->get('limit', 20);
            $logs = $query->paginate($limit);
            
            // Statistik lengkap
            $statsQuery = LogActivity::query();
            if ($user->role === 'manager') {
                $statsQuery->where('user_cabang', $user->cabang);
            }
            
            // Statistik umum
            $stats = [
                'total' => $statsQuery->count(),
                'today' => $statsQuery->whereDate('created_at', today())->count(),
                'this_week' => $statsQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => $statsQuery->whereMonth('created_at', now()->month)->count(),
                'by_module' => $statsQuery->selectRaw('module, count(*) as total')
                    ->groupBy('module')
                    ->get(),
                'by_action' => $statsQuery->selectRaw('action, count(*) as total')
                    ->groupBy('action')
                    ->get(),
            ];
            
            // Statistik autentikasi khusus
            $authStats = [
                'login_success' => LogActivity::where('action', 'LOGIN')
                    ->where('module', 'AUTHENTICATION')
                    ->when($user->role === 'manager', function($q) use ($user) {
                        return $q->where('user_cabang', $user->cabang);
                    })
                    ->count(),
                'login_failed' => LogActivity::where('action', 'LOGIN_FAILED')
                    ->when($user->role === 'manager', function($q) use ($user) {
                        return $q->where('user_cabang', $user->cabang);
                    })
                    ->count(),
                'logout' => LogActivity::where('action', 'LOGOUT')
                    ->when($user->role === 'manager', function($q) use ($user) {
                        return $q->where('user_cabang', $user->cabang);
                    })
                    ->count(),
                'password_changes' => LogActivity::where('action', 'CHANGE_PASSWORD')
                    ->when($user->role === 'manager', function($q) use ($user) {
                        return $q->where('user_cabang', $user->cabang);
                    })
                    ->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $logs,
                'stats' => $stats,
                'auth_stats' => $authStats,
                'filters' => $request->all()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in LogActivityController@index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $log = LogActivity::findOrFail($id);
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Cek akses
            if ($user->role === 'manager' && $log->user_cabang !== $user->cabang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses ke log ini'
                ], 403);
            }
            
            // Parse JSON data jika perlu
            if ($log->old_data) {
                $log->old_data = json_decode($log->old_data, true);
            }
            if ($log->new_data) {
                $log->new_data = json_decode($log->new_data, true);
            }
            
            return response()->json([
                'success' => true,
                'data' => $log
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Log tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in LogActivityController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get login history specifically
     */
    public function loginHistory(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || !in_array($user->role, ['admin', 'manager'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            $query = LogActivity::where('action', 'LOGIN')
                ->where('module', 'AUTHENTICATION');
            
            if ($user->role === 'manager') {
                $query->where('user_cabang', $user->cabang);
            }
            
            // Filter user tertentu
            if ($request->has('user_id') && $request->user_id) {
                $query->where('user_id', $request->user_id);
            }
            
            // Filter tanggal
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $limit = $request->get('limit', 50);
            $logs = $query->orderBy('created_at', 'desc')->paginate($limit);
            
            // Hitung unique users yang login
            $uniqueUsers = $query->distinct('user_id')->count('user_id');
            
            return response()->json([
                'success' => true,
                'data' => $logs,
                'unique_users' => $uniqueUsers,
                'total_logins' => $query->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in loginHistory: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get failed login attempts
     */
    public function failedLogins(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || !in_array($user->role, ['admin', 'manager'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            $query = LogActivity::where('action', 'LOGIN_FAILED');
            
            // Filter IP address
            if ($request->has('ip_address') && $request->ip_address) {
                $query->where('ip_address', $request->ip_address);
            }
            
            // Filter tanggal
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $limit = $request->get('limit', 50);
            $logs = $query->orderBy('created_at', 'desc')->paginate($limit);
            
            // Statistik failed login
            $failedStats = [
                'total' => $query->count(),
                'today' => $query->whereDate('created_at', today())->count(),
                'unique_ips' => $query->distinct('ip_address')->count('ip_address'),
                'top_ips' => LogActivity::where('action', 'LOGIN_FAILED')
                    ->selectRaw('ip_address, count(*) as total')
                    ->groupBy('ip_address')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $logs,
                'stats' => $failedStats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in failedLogins: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get user activity summary
     */
    public function userSummary(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || !$user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied - Hanya admin yang dapat melihat ringkasan user'
                ], 403);
            }
            
            $query = LogActivity::query();
            
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $summary = $query->selectRaw('
                    user_id,
                    user_name,
                    user_role,
                    user_cabang,
                    count(*) as total_activities,
                    sum(case when action = "CREATE" then 1 else 0 end) as creates,
                    sum(case when action = "UPDATE" then 1 else 0 end) as updates,
                    sum(case when action = "DELETE" then 1 else 0 end) as deletes,
                    sum(case when action = "LOGIN" then 1 else 0 end) as logins,
                    max(created_at) as last_activity
                ')
                ->groupBy('user_id', 'user_name', 'user_role', 'user_cabang')
                ->orderBy('total_activities', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in userSummary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || !in_array($user->role, ['admin', 'manager'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            $query = LogActivity::orderBy('created_at', 'desc');
            
            if ($user->role === 'manager') {
                $query->where('user_cabang', $user->cabang);
            }
            
            // Apply filters sama seperti di index
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('user_name', 'like', "%{$search}%")
                      ->orWhere('action', 'like', "%{$search}%")
                      ->orWhere('module', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            if ($request->has('module') && $request->module) {
                $query->where('module', $request->module);
            }
            
            if ($request->has('action') && $request->action) {
                $query->where('action', $request->action);
            }
            
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $logs = $query->get();
            
            $filename = 'log_activity_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($logs) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                fputcsv($file, [
                    'No', 'Tanggal & Waktu', 'User Name', 'User Role', 'Cabang', 
                    'Action', 'Module', 'Description', 'IP Address', 'User Agent'
                ]);
                
                foreach ($logs as $index => $log) {
                    fputcsv($file, [
                        $index + 1,
                        $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-',
                        $log->user_name ?? '-',
                        $log->user_role ?? '-',
                        $log->user_cabang ?? '-',
                        $log->action ?? '-',
                        $log->module ?? '-',
                        $log->description ?? '-',
                        $log->ip_address ?? '-',
                        $log->user_agent ?? '-'
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error exporting logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengexport data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get available modules for filter
     */
    public function getModules()
    {
        try {
            $user = Auth::user();
            
            if (!$user || !in_array($user->role, ['admin', 'manager'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            $query = LogActivity::query();
            if ($user->role === 'manager') {
                $query->where('user_cabang', $user->cabang);
            }
            
            $modules = $query->distinct('module')->pluck('module');
            
            return response()->json([
                'success' => true,
                'data' => $modules
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get available actions for filter
     */
    public function getActions()
    {
        try {
            $user = Auth::user();
            
            if (!$user || !in_array($user->role, ['admin', 'manager'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            $query = LogActivity::query();
            if ($user->role === 'manager') {
                $query->where('user_cabang', $user->cabang);
            }
            
            $actions = $query->distinct('action')->pluck('action');
            
            return response()->json([
                'success' => true,
                'data' => $actions
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}