<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get all dashboard statistics
     */
    public function stats(Request $request)
{
    try {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        // Base query berdasarkan role user
        $query = Kunjungan::query();
        
        if ($user->role === 'ao') {
            $query->where('nama_cabang', $user->cabang)
                  ->where('nama_ao', $user->name);
        } elseif ($user->role === 'manager') {
            $query->where('nama_cabang', $user->cabang);
        }
        // Admin dan supervisor bisa lihat semua
        
        // Filter cabang
        if ($request->has('cabang') && $request->cabang !== 'all') {
            $query->where('nama_cabang', $request->cabang);
        }
        
        // Filter AO
        if ($request->has('ao') && $request->ao !== 'all') {
            $query->where('nama_ao', $request->ao);
        }
        
        // ========== TAMBAHKAN FILTER STATUS ==========
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        // ========== SAMPAI SINI ==========
        
        // Filter tanggal mulai
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal_kunjungan', '>=', $request->start_date);
        }
        
        // Filter tanggal sampai
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal_kunjungan', '<=', $request->end_date);
        }
        // ========== SAMPAI SINI ==========
        
        // 1. Statistik per bulan (6 bulan terakhir)
        $monthlyStats = $this->getMonthlyStats($query);
        
        // 2. Top 5 AO berdasarkan kunjungan
        $topAO = $this->getTopAO($query);
        
        // 3. Distribusi status
        $statusDistribution = $this->getStatusDistribution($query);
        
        // 4. Statistik per cabang
        $cabangStats = $this->getCabangStats($query);
        
        // 5. Tren kunjungan per hari (7 hari terakhir)
        $dailyTrend = $this->getDailyTrend($query);
        
        // 6. Summary card
        $summary = $this->getSummary($query);
        
        return response()->json([
            'success' => true,
            'data' => [
                'monthly_stats' => $monthlyStats,
                'top_ao' => $topAO,
                'status_distribution' => $statusDistribution,
                'cabang_stats' => $cabangStats,
                'daily_trend' => $dailyTrend,
                'summary' => $summary,
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data dashboard: ' . $e->getMessage()
        ], 500);
    }
}
    
    /**
     * Statistik per bulan (6 bulan terakhir)
     */
    private function getMonthlyStats($query)
    {
        $months = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $months[] = $month->format('Y-m');
        }
        
        $stats = [];
        foreach ($months as $index => $month) {
            $count = (clone $query)
                ->whereYear('tanggal_kunjungan', substr($month, 0, 4))
                ->whereMonth('tanggal_kunjungan', substr($month, 5, 2))
                ->count();
            
            $stats[] = [
                'bulan' => $labels[$index],
                'total' => $count,
            ];
        }
        
        return $stats;
    }
    
    /**
     * Top 5 AO berdasarkan jumlah kunjungan
     */
    private function getTopAO($query)
    {
        return (clone $query)
            ->select('nama_ao', DB::raw('COUNT(*) as total'))
            ->groupBy('nama_ao')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_ao' => $item->nama_ao,
                    'total' => $item->total,
                ];
            });
    }
    
    /**
     * Distribusi status (pending, approved, rejected)
     */
    private function getStatusDistribution($query)
    {
        $total = (clone $query)->count();
        
        $stats = (clone $query)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) use ($total) {
                return [
                    'status' => $item->status,
                    'label' => $this->getStatusLabel($item->status),
                    'total' => $item->total,
                    'percentage' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0,
                ];
            });
        
        return $stats;
    }
    
    /**
     * Statistik per cabang
     */
    private function getCabangStats($query)
    {
        return (clone $query)
            ->select('nama_cabang', DB::raw('COUNT(*) as total'))
            ->groupBy('nama_cabang')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'cabang' => $item->nama_cabang,
                    'total' => $item->total,
                ];
            });
    }
    
    /**
     * Tren kunjungan per hari (7 hari terakhir)
     */
    private function getDailyTrend($query)
    {
        $dates = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $dates[] = $date->format('Y-m-d');
        }
        
        $stats = [];
        foreach ($dates as $index => $date) {
            $count = (clone $query)
                ->whereDate('tanggal_kunjungan', $date)
                ->count();
            
            $stats[] = [
                'tanggal' => $labels[$index],
                'total' => $count,
            ];
        }
        
        return $stats;
    }
    
    /**
     * Ringkasan data (card summary)
     */
    private function getSummary($query)
    {
        $total = (clone $query)->count();
        $pending = (clone $query)->where('status', 'pending')->count();
        $approved = (clone $query)->where('status', 'approved')->count();
        $rejected = (clone $query)->where('status', 'rejected')->count();
        
        // Kunjungan hari ini
        $today = now()->format('Y-m-d');
        $todayCount = (clone $query)->whereDate('tanggal_kunjungan', $today)->count();
        
        // Kunjungan bulan ini
        $thisMonth = (clone $query)
            ->whereYear('tanggal_kunjungan', now()->year)
            ->whereMonth('tanggal_kunjungan', now()->month)
            ->count();
        
        // Rata-rata kunjungan per hari bulan ini
        $daysInMonth = now()->daysInMonth;
        $dailyAverage = $daysInMonth > 0 ? round($thisMonth / $daysInMonth, 1) : 0;
        
        // Persentase approval rate
        $approvalRate = $total > 0 ? round(($approved / $total) * 100, 1) : 0;
        
        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'today' => $todayCount,
            'this_month' => $thisMonth,
            'daily_average' => $dailyAverage,
            'approval_rate' => $approvalRate,
        ];
    }
    
    /**
     * Get label for status
     */
    private function getStatusLabel($status)
    {
        return match($status) {
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst($status),
        };
    }
}