<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KunjunganController extends Controller
{
    use LogsActivity;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $query = Kunjungan::orderBy('waktu_input', 'desc');
            
            // Filter data berdasarkan role user (UPDATED for Supervisor)
            if ($user && $user->role === 'ao') {
                $query->where('nama_cabang', $user->cabang)
                      ->where('nama_ao', $user->name);
            } elseif ($user && $user->role === 'manager') {
                $query->where('nama_cabang', $user->cabang);
            } elseif ($user && $user->role === 'supervisor') {
                $viewableCabangs = $user->getViewableCabangs();
                if (!empty($viewableCabangs)) {
                    $query->whereIn('nama_cabang', $viewableCabangs);
                }
            }
            // Admin: tanpa filter (lihat semua)
            
            $data = $query->get();
            
            // Tambahkan URL foto lengkap
            foreach ($data as $item) {
                if ($item->foto_url && !str_starts_with($item->foto_url, 'http')) {
                    $item->foto_url = url($item->foto_url);
                }
            }
            
            // Hitung statistik (UPDATED for Supervisor)
            if ($user && $user->role === 'ao') {
                $stats = [
                    'total_cabang' => 1,
                    'total_kunjungan' => $data->count(),
                    'hari_ini' => $data->where('tanggal_kunjungan', today()->format('Y-m-d'))->count(),
                    'pending' => $data->where('status', 'pending')->count(),
                    'approved' => $data->where('status', 'approved')->count(),
                    'rejected' => $data->where('status', 'rejected')->count(),
                ];
            } elseif ($user && $user->role === 'manager') {
                $stats = [
                    'total_cabang' => 1,
                    'total_kunjungan' => $data->count(),
                    'hari_ini' => $data->where('tanggal_kunjungan', today()->format('Y-m-d'))->count(),
                    'pending' => $data->where('status', 'pending')->count(),
                    'approved' => $data->where('status', 'approved')->count(),
                    'rejected' => $data->where('status', 'rejected')->count(),
                ];
            } elseif ($user && $user->role === 'supervisor') {
                $viewableCabangs = $user->getViewableCabangs();
                $stats = [
                    'total_cabang' => count($viewableCabangs),
                    'total_kunjungan' => $data->count(),
                    'hari_ini' => $data->where('tanggal_kunjungan', today()->format('Y-m-d'))->count(),
                    'pending' => $data->where('status', 'pending')->count(),
                    'approved' => $data->where('status', 'approved')->count(),
                    'rejected' => $data->where('status', 'rejected')->count(),
                ];
            } else {
                $stats = [
                    'total_cabang' => Kunjungan::distinct('nama_cabang')->count('nama_cabang'),
                    'total_kunjungan' => Kunjungan::count(),
                    'hari_ini' => Kunjungan::whereDate('tanggal_kunjungan', today())->count(),
                    'pending' => Kunjungan::where('status', 'pending')->count(),
                    'approved' => Kunjungan::where('status', 'approved')->count(),
                    'rejected' => Kunjungan::where('status', 'rejected')->count(),
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'stats' => $stats,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get pending count for notification
     */
    public function getPendingCount(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $count = 0;
            $newData = 0;
            $lastCheck = $request->session()->get('last_notification_check', now()->subMinutes(30));
            
            if ($user->role === 'admin') {
                $count = Kunjungan::where('status', 'pending')->count();
                $newData = Kunjungan::where('status', 'pending')
                    ->where('created_at', '>', $lastCheck)
                    ->count();
            } elseif ($user->role === 'supervisor') {
                $viewableCabangs = $user->getViewableCabangs();
                $query = Kunjungan::where('status', 'pending');
                if (!empty($viewableCabangs)) {
                    $query->whereIn('nama_cabang', $viewableCabangs);
                }
                $count = $query->count();
                
                $newDataQuery = Kunjungan::where('status', 'pending')
                    ->where('created_at', '>', $lastCheck);
                if (!empty($viewableCabangs)) {
                    $newDataQuery->whereIn('nama_cabang', $viewableCabangs);
                }
                $newData = $newDataQuery->count();
            } elseif ($user->role === 'manager') {
                $count = Kunjungan::where('nama_cabang', $user->cabang)
                    ->where('status', 'pending')
                    ->count();
                $newData = Kunjungan::where('nama_cabang', $user->cabang)
                    ->where('status', 'pending')
                    ->where('created_at', '>', $lastCheck)
                    ->count();
            } else {
                $count = 0;
                $newData = 0;
            }
            
            // Update last check time
            $request->session()->put('last_notification_check', now());
            
            return response()->json([
                'success' => true,
                'pending_count' => $count,
                'new_count' => $newData,
                'last_check' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getPendingCount: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan jumlah pending: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get latest kunjungan for notification
     */
    public function getLatestKunjungan(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $query = Kunjungan::orderBy('created_at', 'desc');
            
            if ($user->role === 'admin') {
                // Admin: semua data
            } elseif ($user->role === 'supervisor') {
                $viewableCabangs = $user->getViewableCabangs();
                if (!empty($viewableCabangs)) {
                    $query->whereIn('nama_cabang', $viewableCabangs);
                }
            } elseif ($user->role === 'manager') {
                $query->where('nama_cabang', $user->cabang);
            } elseif ($user->role === 'ao') {
                $query->where('nama_cabang', $user->cabang)
                      ->where('nama_ao', $user->name);
            }
            
            $latest = $query->limit(10)->get();
            
            // Tambahkan URL foto
            foreach ($latest as $item) {
                if ($item->foto_url && !str_starts_with($item->foto_url, 'http')) {
                    $item->foto_url = url($item->foto_url);
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $latest
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getLatestKunjungan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan data terbaru: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_cabang' => 'required|string',
                'nama_ao' => 'required|string',
                'nama_nasabah' => 'required|string',
                'no_pembiayaan' => 'required|string',
                'alamat' => 'required|string',
                'tanggal_kunjungan' => 'required|date',
                'keterangan' => 'nullable|string',
                'hasil_kunjungan' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);
            
            $data = $request->except('foto', '_method');
            $data['waktu_input'] = now();
            $data['status'] = 'pending';
            
            $fotoUploaded = false;
            if ($request->hasFile('foto')) {
                $fotoPath = $this->uploadFoto($request->file('foto'));
                if ($fotoPath) {
                    $data['foto_url'] = $fotoPath;
                    $fotoUploaded = true;
                }
            }
            
            $kunjungan = Kunjungan::create($data);
            
            // Log activity
            $description = "Menambah data kunjungan nasabah: {$kunjungan->nama_nasabah}";
            $description .= " (Cabang: {$kunjungan->nama_cabang}, AO: {$kunjungan->nama_ao})";
            $description .= " - No Pembiayaan: {$kunjungan->no_pembiayaan}";
            $description .= " - Hasil Kunjungan: {$kunjungan->hasil_kunjungan}";
            if ($fotoUploaded) {
                $description .= " - Dengan foto";
            }
            
            $this->logActivity(
                'CREATE',
                'KUNJUNGAN',
                $description,
                null,
                $kunjungan->toArray()
            );
            
            Log::info('Data kunjungan berhasil disimpan', [
                'id' => $kunjungan->id,
                'nasabah' => $kunjungan->nama_nasabah,
                'user' => Auth::user()->name,
                'cabang' => $kunjungan->nama_cabang,
                'hasil_kunjungan' => $kunjungan->hasil_kunjungan,
                'has_foto' => $fotoUploaded
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $kunjungan
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing kunjungan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $kunjungan = Kunjungan::findOrFail($id);
            
            // Cek akses (UPDATED for Supervisor)
            if ($user->role === 'ao' && ($kunjungan->nama_ao !== $user->name || $kunjungan->nama_cabang !== $user->cabang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke data ini'
                ], 403);
            }
            
            if ($user->role === 'manager' && $kunjungan->nama_cabang !== $user->cabang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke data ini'
                ], 403);
            }
            
            if ($user->role === 'supervisor' && !$user->canViewCabang($kunjungan->nama_cabang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke data ini'
                ], 403);
            }
            
            // Tambahkan URL foto lengkap
            if ($kunjungan->foto_url && !str_starts_with($kunjungan->foto_url, 'http')) {
                $kunjungan->foto_url = url($kunjungan->foto_url);
            }
            
            return response()->json([
                'success' => true,
                'data' => $kunjungan
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing kunjungan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $user = Auth::user();
            $oldData = $kunjungan->toArray();
            
            Log::info('Update called', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'kunjungan_id' => $id,
                'current_status' => $kunjungan->status
            ]);
            
            if ($user->role === 'ao') {
                if ($kunjungan->nama_ao !== $user->name) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat mengedit data milik sendiri'
                    ], 403);
                }
                
                if ($kunjungan->status === 'approved') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data yang sudah disetujui tidak dapat diedit'
                    ], 403);
                }
            }
            
            if ($user->role === 'manager') {
                return response()->json([
                    'success' => false,
                    'message' => 'Manager tidak dapat mengedit data'
                ], 403);
            }
            
            if ($user->role === 'supervisor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Supervisor tidak dapat mengedit data'
                ], 403);
            }
            
            $request->validate([
                'nama_cabang' => 'required|string',
                'nama_ao' => 'required|string',
                'nama_nasabah' => 'required|string',
                'no_pembiayaan' => 'required|string',
                'alamat' => 'required|string',
                'tanggal_kunjungan' => 'required|date',
                'keterangan' => 'nullable|string',
                'hasil_kunjungan' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);
            
            $data = $request->except('foto', '_method');
            $fotoChanged = false;
            
            if ($request->hasFile('foto')) {
                if ($kunjungan->foto_url) {
                    $this->deleteFoto($kunjungan->foto_url);
                }
                $fotoPath = $this->uploadFoto($request->file('foto'));
                if ($fotoPath) {
                    $data['foto_url'] = $fotoPath;
                    $fotoChanged = true;
                }
            }
            
            $statusReset = false;
            if ($kunjungan->status === 'rejected' && $user->role === 'ao') {
                $data['status'] = 'pending';
                $data['catatan_manager'] = null;
                $statusReset = true;
                Log::info('Reset status dari rejected ke pending', ['id' => $id]);
            }
            
            $kunjungan->update($data);
            
            // Build deskripsi perubahan
            $changes = [];
            if ($oldData['nama_nasabah'] !== $kunjungan->nama_nasabah) {
                $changes[] = "Nama nasabah: {$oldData['nama_nasabah']} → {$kunjungan->nama_nasabah}";
            }
            if ($oldData['no_pembiayaan'] !== $kunjungan->no_pembiayaan) {
                $changes[] = "No Pembiayaan: {$oldData['no_pembiayaan']} → {$kunjungan->no_pembiayaan}";
            }
            if ($oldData['tanggal_kunjungan'] !== $kunjungan->tanggal_kunjungan) {
                $changes[] = "Tanggal kunjungan: {$oldData['tanggal_kunjungan']} → {$kunjungan->tanggal_kunjungan}";
            }
            if (($oldData['hasil_kunjungan'] ?? '') !== ($kunjungan->hasil_kunjungan ?? '')) {
                $changes[] = "Hasil Kunjungan: {$oldData['hasil_kunjungan']} → {$kunjungan->hasil_kunjungan}";
            }
            if ($fotoChanged) {
                $changes[] = "Foto diperbarui";
            }
            if ($statusReset) {
                $changes[] = "Status direset dari rejected ke pending";
            }
            
            $description = "Mengedit data kunjungan nasabah: {$kunjungan->nama_nasabah}";
            if (!empty($changes)) {
                $description .= " - " . implode(", ", $changes);
            }
            
            $this->logActivity(
                'UPDATE',
                'KUNJUNGAN',
                $description,
                $oldData,
                $kunjungan->toArray()
            );
            
            Log::info('Data kunjungan berhasil diupdate', [
                'id' => $kunjungan->id,
                'user' => $user->name,
                'old_status' => $oldData['status'],
                'new_status' => $kunjungan->status,
                'hasil_kunjungan' => $kunjungan->hasil_kunjungan,
                'changes' => $changes
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating kunjungan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal update: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $user = Auth::user();
            $oldData = $kunjungan->toArray();
            
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya administrator yang dapat menghapus data'
                ], 403);
            }
            
            $description = "Menghapus data kunjungan nasabah: {$kunjungan->nama_nasabah}";
            $description .= " (Cabang: {$kunjungan->nama_cabang}, AO: {$kunjungan->nama_ao})";
            $description .= " - No Pembiayaan: {$kunjungan->no_pembiayaan}";
            $description .= " - Status: {$kunjungan->status}";
            $description .= " - Hasil Kunjungan: {$kunjungan->hasil_kunjungan}";
            if ($kunjungan->foto_url) {
                $description .= " - Dengan foto";
            }
            
            $this->logActivity(
                'DELETE',
                'KUNJUNGAN',
                $description,
                $oldData,
                null
            );
            
            if ($kunjungan->foto_url) {
                $this->deleteFoto($kunjungan->foto_url);
            }
            
            $kunjungan->delete();
            
            Log::info('Data kunjungan berhasil dihapus', [
                'id' => $id,
                'nasabah' => $kunjungan->nama_nasabah,
                'user' => $user->name,
                'cabang' => $kunjungan->nama_cabang,
                'hasil_kunjungan' => $kunjungan->hasil_kunjungan
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting kunjungan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update catatan manager/supervisor
     */
    public function updateCatatan(Request $request, $id)
    {
        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $user = Auth::user();
            $oldData = $kunjungan->toArray();
            
            // Supervisor bisa menambah catatan (tanpa approve/reject)
            if ($user->role === 'admin') {
                // Admin bisa edit semua
            } elseif ($user->role === 'supervisor') {
                // Supervisor bisa lihat dan beri catatan untuk cabang binaan
                $viewableCabangs = $user->getViewableCabangs();
                if (!empty($viewableCabangs) && !in_array($kunjungan->nama_cabang, $viewableCabangs)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke data ini'
                    ], 403);
                }
            } elseif ($user->role === 'manager') {
                if ($kunjungan->nama_cabang !== $user->cabang) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat mengelola data dari cabang ' . $user->cabang
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak memiliki akses'
                ], 403);
            }
            
            $request->validate([
                'catatan_manager' => 'nullable|string|max:1000'
            ]);
            
            // Tambahkan prefix untuk membedakan siapa yang memberi catatan
            $prefix = $user->role === 'supervisor' ? '[SUPERVISOR] ' : ($user->role === 'manager' ? '[MANAGER] ' : '[ADMIN] ');
            $newCatatan = $prefix . ($request->catatan_manager ?? '');
            
            $existingCatatan = $kunjungan->catatan_manager;
            $finalCatatan = $existingCatatan 
                ? $existingCatatan . "\n" . $newCatatan 
                : $newCatatan;
            
            $kunjungan->update([
                'catatan_manager' => $finalCatatan
            ]);
            
            $description = $user->role === 'supervisor'
                ? "Supervisor menambahkan catatan untuk kunjungan nasabah: {$kunjungan->nama_nasabah}"
                : ($user->role === 'manager' 
                    ? "Manager mengupdate catatan untuk kunjungan nasabah: {$kunjungan->nama_nasabah}"
                    : "Admin mengupdate catatan untuk kunjungan nasabah: {$kunjungan->nama_nasabah}");
            
            $this->logActivity(
                'UPDATE_CATATAN',
                'KUNJUNGAN',
                $description,
                ['old_catatan' => $oldData['catatan_manager']],
                ['new_catatan' => $finalCatatan]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil disimpan'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating catatan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan catatan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Approve kunjungan (dengan catatan opsional)
     */
    public function approve(Request $request, $id)
    {
        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $user = Auth::user();
            $oldData = $kunjungan->toArray();
            
            if ($user->role === 'admin') {
                // Admin bisa approve semua
            } elseif ($user->role === 'manager') {
                if ($kunjungan->nama_cabang !== $user->cabang) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat mengelola data dari cabang ' . $user->cabang
                    ], 403);
                }
            } elseif ($user->role === 'supervisor') {
                // Supervisor bisa approve untuk cabang binaan
                if (!$user->canViewCabang($kunjungan->nama_cabang)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke cabang ini'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Supervisor/Manager/Admin yang dapat menyetujui data'
                ], 403);
            }
            
            if ($kunjungan->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sudah disetujui sebelumnya'
                ], 400);
            }
            
            $previousStatus = $kunjungan->status;
            
            // Ambil catatan dari request (opsional)
            $catatan = $request->input('catatan_manager');
            
            $updateData = [
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $user->id,
                'rejected_by' => null,
                'rejected_at' => null
            ];
            
            // Jika ada catatan, simpan dengan prefix role
            if ($catatan && !empty($catatan)) {
                $prefix = $user->role === 'supervisor' ? '[SUPERVISOR] ' : ($user->role === 'manager' ? '[MANAGER] ' : '[ADMIN] ');
                $newCatatan = $prefix . $catatan;
                
                if ($kunjungan->catatan_manager) {
                    $updateData['catatan_manager'] = $kunjungan->catatan_manager . "\n" . $newCatatan;
                } else {
                    $updateData['catatan_manager'] = $newCatatan;
                }
            }
            
            $kunjungan->update($updateData);
            
            $description = "Menyetujui data kunjungan nasabah: {$kunjungan->nama_nasabah}";
            $description .= " (Cabang: {$kunjungan->nama_cabang}, AO: {$kunjungan->nama_ao})";
            $description .= " - Hasil Kunjungan: {$kunjungan->hasil_kunjungan}";
            $description .= " - Status sebelumnya: " . ($previousStatus === 'pending' ? 'Pending' : 'Rejected');
            if ($catatan) {
                $description .= " - Catatan: {$catatan}";
            }
            
            $this->logActivity(
                'APPROVE',
                'KUNJUNGAN',
                $description,
                $oldData,
                $kunjungan->toArray()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disetujui' . ($catatan ? ' dengan catatan' : '')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error approving kunjungan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject kunjungan dengan catatan (wajib)
     */
    public function reject(Request $request, $id)
    {
        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $user = Auth::user();
            $oldData = $kunjungan->toArray();
            
            if ($user->role === 'admin') {
                // Admin bisa reject semua
            } elseif ($user->role === 'manager') {
                if ($kunjungan->nama_cabang !== $user->cabang) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat menolak data dari cabang ' . $user->cabang
                    ], 403);
                }
            } elseif ($user->role === 'supervisor') {
                if (!$user->canViewCabang($kunjungan->nama_cabang)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke cabang ini'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Supervisor/Manager/Admin yang dapat menolak data'
                ], 403);
            }
            
            if ($kunjungan->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang sudah disetujui tidak dapat ditolak. Batalkan persetujuan terlebih dahulu.'
                ], 400);
            }
            
            $request->validate([
                'catatan_manager' => 'required|string|min:3|max:500'
            ]);
            
            $previousStatus = $kunjungan->status;
            
            // Tambahkan prefix role pada catatan penolakan
            $prefix = $user->role === 'supervisor' ? '[SUPERVISOR] ' : ($user->role === 'manager' ? '[MANAGER] ' : '[ADMIN] ');
            $finalCatatan = $prefix . $request->catatan_manager;
            
            $kunjungan->update([
                'status' => 'rejected',
                'catatan_manager' => $finalCatatan,
                'rejected_at' => now(),
                'rejected_by' => $user->id,
                'approved_by' => null,
                'approved_at' => null
            ]);
            
            $description = "Menolak data kunjungan nasabah: {$kunjungan->nama_nasabah}";
            $description .= " (Cabang: {$kunjungan->nama_cabang}, AO: {$kunjungan->nama_ao})";
            $description .= " - Hasil Kunjungan: {$kunjungan->hasil_kunjungan}";
            $description .= " - Alasan penolakan: {$request->catatan_manager}";
            
            $this->logActivity(
                'REJECT',
                'KUNJUNGAN',
                $description,
                $oldData,
                $kunjungan->toArray()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditolak',
                'data' => $kunjungan
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan wajib diisi minimal 3 karakter untuk menolak data'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error rejecting kunjungan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Cancel approve - membatalkan persetujuan
     */
    public function cancelApprove($id)
    {
        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $user = Auth::user();
            $oldData = $kunjungan->toArray();
            
            if ($user->role !== 'manager' && $user->role !== 'admin' && $user->role !== 'supervisor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Supervisor/Manager/Admin yang dapat membatalkan persetujuan'
                ], 403);
            }
            
            if ($user->role === 'manager' && $kunjungan->nama_cabang !== $user->cabang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya dapat mengelola data dari cabang ' . $user->cabang
                ], 403);
            }
            
            if ($user->role === 'supervisor' && !$user->canViewCabang($kunjungan->nama_cabang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke cabang ini'
                ], 403);
            }
            
            if ($kunjungan->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya data yang sudah disetujui yang dapat dibatalkan persetujuannya'
                ], 400);
            }
            
            $kunjungan->update([
                'status' => 'pending',
                'approved_by' => null,
                'approved_at' => null
            ]);
            
            $description = "Membatalkan persetujuan kunjungan nasabah: {$kunjungan->nama_nasabah}";
            $description .= " (Cabang: {$kunjungan->nama_cabang}, AO: {$kunjungan->nama_ao})";
            $description .= " - Hasil Kunjungan: {$kunjungan->hasil_kunjungan}";
            
            $this->logActivity(
                'CANCEL_APPROVE',
                'KUNJUNGAN',
                $description,
                $oldData,
                $kunjungan->toArray()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Persetujuan berhasil dibatalkan, data kembali ke status Pending'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in cancelApprove: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan persetujuan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export data ke CSV
     */
    public function export(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Kunjungan::orderBy('waktu_input', 'desc');
            
            if ($user->role === 'ao') {
                $query->where('nama_cabang', $user->cabang)
                      ->where('nama_ao', $user->name);
            } elseif ($user->role === 'manager') {
                $query->where('nama_cabang', $user->cabang);
            } elseif ($user->role === 'supervisor') {
                $viewableCabangs = $user->getViewableCabangs();
                if (!empty($viewableCabangs)) {
                    $query->whereIn('nama_cabang', $viewableCabangs);
                }
            }
            // Admin: tanpa filter
            
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_ao', 'like', "%{$search}%")
                      ->orWhere('nama_cabang', 'like', "%{$search}%")
                      ->orWhere('nama_nasabah', 'like', "%{$search}%")
                      ->orWhere('no_pembiayaan', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%");
                });
            }
            
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('tanggal_kunjungan', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('tanggal_kunjungan', '<=', $request->end_date);
            }
            
            $data = $query->get();
            
            $filename = 'kunjungan_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                fputcsv($file, [
                    'No', 'Cabang', 'Nama AO', 'Nama Nasabah', 'No Pembiayaan', 
                    'Alamat', 'Tanggal Kunjungan', 'Keterangan', 'Hasil Kunjungan',
                    'Waktu Input', 'Status', 'Catatan'
                ]);
                
                foreach ($data as $index => $item) {
                    $statusText = $item->status === 'approved' ? 'Disetujui' : 
                                  ($item->status === 'rejected' ? 'Ditolak' : 'Pending');
                    
                    fputcsv($file, [
                        $index + 1,
                        $item->nama_cabang,
                        $item->nama_ao,
                        $item->nama_nasabah,
                        $item->no_pembiayaan,
                        $item->alamat,
                        $item->tanggal_kunjungan,
                        $item->keterangan ?? '-',
                        $item->hasil_kunjungan ?? '-',
                        $item->waktu_input ? date('d/m/Y H:i', strtotime($item->waktu_input)) : '-',
                        $statusText,
                        $item->catatan_manager ?? '-'
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error exporting data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengexport data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get statistics for dashboard
     */
    public function statistics(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Kunjungan::query();
            
            if ($user->role === 'ao') {
                $query->where('nama_cabang', $user->cabang)
                      ->where('nama_ao', $user->name);
            } elseif ($user->role === 'manager') {
                $query->where('nama_cabang', $user->cabang);
            } elseif ($user->role === 'supervisor') {
                $viewableCabangs = $user->getViewableCabangs();
                if (!empty($viewableCabangs)) {
                    $query->whereIn('nama_cabang', $viewableCabangs);
                }
            }
            // Admin: tanpa filter
            
            $monthlyStats = $query->selectRaw('
                    DATE_FORMAT(tanggal_kunjungan, "%Y-%m") as bulan,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
                    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending
                ')
                ->whereYear('tanggal_kunjungan', date('Y'))
                ->groupBy('bulan')
                ->orderBy('bulan', 'desc')
                ->get();
            
            $aoStats = $query->selectRaw('
                    nama_ao,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected
                ')
                ->groupBy('nama_ao')
                ->orderBy('total', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'monthly' => $monthlyStats,
                'by_ao' => $aoStats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk approve multiple kunjungan
     */
    public function bulkApprove(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manager' && $user->role !== 'admin' && $user->role !== 'supervisor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Supervisor/Manager/Admin yang dapat menyetujui data'
                ], 403);
            }
            
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:kunjungans,id'
            ]);
            
            $ids = $request->ids;
            $approvedCount = 0;
            $skippedCount = 0;
            
            foreach ($ids as $id) {
                $kunjungan = Kunjungan::find($id);
                
                // Cek akses berdasarkan role
                if ($user->role === 'manager' && $kunjungan->nama_cabang !== $user->cabang) {
                    $skippedCount++;
                    continue;
                }
                
                if ($user->role === 'supervisor' && !$user->canViewCabang($kunjungan->nama_cabang)) {
                    $skippedCount++;
                    continue;
                }
                
                if ($kunjungan->status === 'approved') {
                    $skippedCount++;
                    continue;
                }
                
                $oldData = $kunjungan->toArray();
                
                $kunjungan->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => $user->id,
                    'rejected_by' => null,
                    'rejected_at' => null
                ]);
                
                $this->logActivity(
                    'BULK_APPROVE',
                    'KUNJUNGAN',
                    "Menyetujui kunjungan nasabah: {$kunjungan->nama_nasabah} dalam operasi bulk approve",
                    $oldData,
                    $kunjungan->toArray()
                );
                
                $approvedCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menyetujui {$approvedCount} data kunjungan" . ($skippedCount > 0 ? " ({$skippedCount} data dilewati)" : ""),
                'data' => [
                    'approved_count' => $approvedCount,
                    'skipped_count' => $skippedCount
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in bulkApprove: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan bulk approve: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk delete multiple kunjungan (admin only)
     */
    public function bulkDelete(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat menghapus massal'
                ], 403);
            }
            
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:kunjungans,id'
            ]);
            
            $kunjungans = Kunjungan::whereIn('id', $request->ids)->get();
            $deletedCount = 0;
            $deletedNasabah = [];
            
            foreach ($kunjungans as $kunjungan) {
                $deletedNasabah[] = $kunjungan->nama_nasabah;
                
                if ($kunjungan->foto_url) {
                    $this->deleteFoto($kunjungan->foto_url);
                }
                
                $kunjungan->delete();
                $deletedCount++;
            }
            
            $this->logActivity(
                'BULK_DELETE',
                'KUNJUNGAN',
                "Menghapus {$deletedCount} data kunjungan secara massal. Nasabah: " . implode(', ', $deletedNasabah),
                null,
                ['ids' => $request->ids, 'deleted_count' => $deletedCount, 'deleted_nasabah' => $deletedNasabah]
            );
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data kunjungan"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in bulkDelete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get notification summary
     */
    public function getNotificationSummary(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $query = Kunjungan::query();
            
            if ($user->role === 'manager') {
                $query->where('nama_cabang', $user->cabang);
            } elseif ($user->role === 'supervisor') {
                $viewableCabangs = $user->getViewableCabangs();
                if (!empty($viewableCabangs)) {
                    $query->whereIn('nama_cabang', $viewableCabangs);
                }
            } elseif ($user->role === 'ao') {
                $query->where('nama_cabang', $user->cabang)
                      ->where('nama_ao', $user->name);
            }
            
            $summary = [
                'total_pending' => (clone $query)->where('status', 'pending')->count(),
                'today_created' => (clone $query)->whereDate('created_at', today())->count(),
                'week_created' => (clone $query)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'last_24h' => (clone $query)->where('created_at', '>', now()->subDay())->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getNotificationSummary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan ringkasan notifikasi'
            ], 500);
        }
    }
    
    // ========== PRIVATE HELPER METHODS ==========
    
    private function uploadFoto($file)
    {
        try {
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/foto_kunjungan');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            return '/uploads/foto_kunjungan/' . $filename;
        } catch (\Exception $e) {
            Log::error('Error uploading foto: ' . $e->getMessage());
            return null;
        }
    }
    
    private function deleteFoto($fotoUrl)
    {
        try {
            if ($fotoUrl) {
                $publicPath = public_path(parse_url($fotoUrl, PHP_URL_PATH));
                if (file_exists($publicPath)) {
                    @unlink($publicPath);
                }
                $filename = basename($fotoUrl);
                Storage::disk('public')->delete('foto_kunjungan/' . $filename);
            }
        } catch (\Exception $e) {
            Log::warning('Error deleting foto: ' . $e->getMessage());
        }
    }
}