<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogActivityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Clear cache routes
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Cache cleared successfully!';
});

// Storage link
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully!';
});

// Route utama
Route::get('/', function () {
    return view('app');
});

// Catch all route untuk SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    
    // ========== PUBLIC ROUTES (No Auth - BISA DIAKSES SEBELUM LOGIN) ==========
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check-auth', [AuthController::class, 'checkAuth']);  // ← PINDAHKAN KE SINI!
    
    // ========== PROTECTED ROUTES (Harus sudah login) ==========
    Route::middleware(['auth'])->group(function () {
        
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/verify-password', [AuthController::class, 'verifyPassword']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::get('/login-history', [AuthController::class, 'loginHistory']);
        
        // User Management
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::get('/users/role/{role}', [UserController::class, 'getByRole']);
        Route::get('/users/supervisors/list', [UserController::class, 'getSupervisors']);
        Route::get('/users/ao/list', [UserController::class, 'getAOList']);
        Route::get('/users/cabang/ao', [UserController::class, 'getByCabang']);
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
        Route::post('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);
        Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete']);
        Route::get('/users/export/csv', [UserController::class, 'export']);
        
        // Kunjungan - Basic CRUD
        Route::get('/kunjungan', [KunjunganController::class, 'index']);
        Route::get('/kunjungan/{id}', [KunjunganController::class, 'show']);
        Route::post('/kunjungan', [KunjunganController::class, 'store']);
        Route::put('/kunjungan/{id}', [KunjunganController::class, 'update']);
        Route::delete('/kunjungan/{id}', [KunjunganController::class, 'destroy']);
        
        // Export
        Route::get('/export', [KunjunganController::class, 'export']);
        Route::get('/kunjungan/export/csv', [KunjunganController::class, 'export']);
        
        // Manager/Supervisor Features
        Route::post('/kunjungan/{id}/catatan', [KunjunganController::class, 'updateCatatan']);
        Route::post('/kunjungan/{id}/approve', [KunjunganController::class, 'approve']);
        Route::post('/kunjungan/{id}/reject', [KunjunganController::class, 'reject']);
        Route::post('/kunjungan/{id}/cancel-approve', [KunjunganController::class, 'cancelApprove']);
        
        // Notification Routes
        Route::get('/kunjungan/pending/count', [KunjunganController::class, 'getPendingCount']);
        Route::get('/kunjungan/latest', [KunjunganController::class, 'getLatestKunjungan']);
        Route::get('/kunjungan/notifications/summary', [KunjunganController::class, 'getNotificationSummary']);
        
        // Statistics Routes
        Route::get('/kunjungan/statistics/dashboard', [KunjunganController::class, 'statistics']);
        Route::get('/dashboard/stats', [KunjunganController::class, 'statistics']);
        
        // Bulk Operations
        Route::post('/kunjungan/bulk/approve', [KunjunganController::class, 'bulkApprove']);
        Route::post('/kunjungan/bulk/delete', [KunjunganController::class, 'bulkDelete']);
        
        // Log Activity Routes
        Route::get('/log-activities', [LogActivityController::class, 'index']);
        Route::get('/log-activities/{id}', [LogActivityController::class, 'show']);
        Route::get('/log-activities/export/csv', [LogActivityController::class, 'export']);
        Route::get('/log-activities/user/{userId}', [LogActivityController::class, 'getByUser']);
        
    });
    
});

/*
|--------------------------------------------------------------------------
| File Access Routes (Public)
|--------------------------------------------------------------------------
*/

Route::get('/foto/{filename}', function ($filename) {
    try {
        $decodedFilename = urldecode($filename);
        
        $paths = [
            storage_path('app/public/foto_kunjungan/' . $decodedFilename),
            public_path('uploads/foto_kunjungan/' . $decodedFilename),
            storage_path('app/foto_kunjungan/' . $decodedFilename),
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path) && is_file($path)) {
                return response()->file($path, [
                    'Content-Type' => mime_content_type($path),
                    'Cache-Control' => 'public, max-age=31536000',
                    'Access-Control-Allow-Origin' => '*'
                ]);
            }
        }
        
        $noImagePath = public_path('images/no-image.png');
        if (file_exists($noImagePath)) {
            return response()->file($noImagePath);
        }
        
        return response()->json(['error' => 'File not found'], 404);
        
    } catch (\Exception $e) {
        return response()->json(['error' => 'File not found'], 404);
    }
})->where('filename', '.*');

// ========== TEST ROUTES (Development only) ==========
if (app()->environment('local')) {
    Route::get('/test-db', function () {
        try {
            \DB::connection()->getPdo();
            return response()->json([
                'success' => true,
                'message' => 'Database connected!',
                'database' => \DB::connection()->getDatabaseName()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ], 500);
        }
    });

    Route::get('/test-auth', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user() ? [
                'id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'username' => auth()->user()->username,
                'role' => auth()->user()->role,
                'cabang' => auth()->user()->cabang,
            ] : null,
        ]);
    });
}