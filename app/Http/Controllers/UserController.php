<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use LogsActivity;
    
    /**
     * Display a listing of users (Admin only)
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Hanya admin yang bisa lihat semua user
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Hanya admin yang dapat mengakses'
                ], 403);
            }
            
            $users = User::orderBy('role')->orderBy('name')->get();
            
            // Log aktivitas melihat daftar user
            $this->logActivity(
                'VIEW',
                'USER',
                "Melihat daftar user. Total user: {$users->count()}",
                null,
                ['total' => $users->count(), 'roles' => $users->groupBy('role')->map->count()]
            );
            
            return response()->json([
                'success' => true,
                'data' => $users->makeHidden(['password'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Hanya admin yang bisa menambah user
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Hanya admin yang dapat menambah user'
                ], 403);
            }
            
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:4',
                'role' => 'required|in:ao,manager,supervisor,admin',
                'cabang' => 'required_if:role,ao,manager|nullable|string',
                'cabang_binaan' => 'nullable|array'
            ]);
            
            // Validasi tambahan untuk AO dan Manager
            if (in_array($request->role, ['ao', 'manager']) && empty($request->cabang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang wajib diisi untuk role AO dan Manager'
                ], 422);
            }
            
            // Validasi untuk Supervisor
            if ($request->role === 'supervisor' && empty($request->cabang_binaan)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang binaan wajib diisi untuk role Supervisor'
                ], 422);
            }
            
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => true
            ];
            
            // Set level berdasarkan role
            $levelMap = [
                'ao' => 1,
                'manager' => 2,
                'supervisor' => 3,
                'admin' => 4,
            ];
            $data['level'] = $levelMap[$request->role];
            
            // Set cabang untuk AO dan Manager
            if (in_array($request->role, ['ao', 'manager'])) {
                $data['cabang'] = $request->cabang;
                $data['cabang_binaan'] = null;
            }
            
            // Set cabang_binaan untuk Supervisor
            if ($request->role === 'supervisor') {
                $data['cabang'] = null;
                $data['cabang_binaan'] = $request->cabang_binaan;
            }
            
            // Admin tidak perlu cabang
            if ($request->role === 'admin') {
                $data['cabang'] = null;
                $data['cabang_binaan'] = null;
            }
            
            $newUser = User::create($data);
            
            // Log activity CREATE
            $cabangInfo = '';
            if (in_array($newUser->role, ['ao', 'manager'])) {
                $cabangInfo = " - Cabang: {$newUser->cabang}";
            } elseif ($newUser->role === 'supervisor') {
                $cabangInfo = " - Cabang Binaan: " . implode(', ', $newUser->cabang_binaan ?? []);
            }
            
            $this->logActivity(
                'CREATE',
                'USER',
                "Menambah user baru: {$newUser->name} (Username: {$newUser->username}) dengan role {$newUser->role}{$cabangInfo}",
                null,
                $newUser->toArray()
            );
            
            // Hapus password dari response
            $newUser->makeHidden(['password']);
            
            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan',
                'data' => $newUser
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in UserController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            // Hanya admin yang bisa update user
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Hanya admin yang dapat mengupdate user'
                ], 403);
            }
            
            $targetUser = User::findOrFail($id);
            $oldData = $targetUser->toArray();
            
            // Cegah admin mengubah role sendiri menjadi selain admin
            if ($user->id == $targetUser->id && $request->has('role') && $request->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mengubah role sendiri menjadi non-admin'
                ], 400);
            }
            
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username,' . $id,
                'password' => 'nullable|string|min:4',
                'role' => 'required|in:ao,manager,supervisor,admin',
                'cabang' => 'required_if:role,ao,manager|nullable|string',
                'cabang_binaan' => 'nullable|array'
            ]);
            
            // Validasi untuk AO dan Manager
            if (in_array($request->role, ['ao', 'manager']) && empty($request->cabang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang wajib diisi untuk role AO dan Manager'
                ], 422);
            }
            
            // Validasi untuk Supervisor
            if ($request->role === 'supervisor' && empty($request->cabang_binaan)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang binaan wajib diisi untuk role Supervisor'
                ], 422);
            }
            
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'role' => $request->role,
            ];
            
            // Update password jika diisi
            $passwordChanged = false;
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
                $passwordChanged = true;
            }
            
            // Update level berdasarkan role
            $levelMap = ['ao' => 1, 'manager' => 2, 'supervisor' => 3, 'admin' => 4];
            $data['level'] = $levelMap[$request->role];
            
            // Set cabang/cabang_binaan berdasarkan role
            if (in_array($request->role, ['ao', 'manager'])) {
                $data['cabang'] = $request->cabang;
                $data['cabang_binaan'] = null;
            } elseif ($request->role === 'supervisor') {
                $data['cabang'] = null;
                $data['cabang_binaan'] = $request->cabang_binaan;
            } else {
                $data['cabang'] = null;
                $data['cabang_binaan'] = null;
            }
            
            $targetUser->update($data);
            
            // Build deskripsi perubahan
            $description = "Mengedit user: {$targetUser->name} (Username: {$targetUser->username})";
            $changes = [];
            
            if ($passwordChanged) {
                $changes[] = "Password diubah";
            }
            if ($oldData['name'] !== $targetUser->name) {
                $changes[] = "Nama dari '{$oldData['name']}' menjadi '{$targetUser->name}'";
            }
            if ($oldData['username'] !== $targetUser->username) {
                $changes[] = "Username dari '{$oldData['username']}' menjadi '{$targetUser->username}'";
            }
            if ($oldData['role'] !== $targetUser->role) {
                $changes[] = "Role dari '{$oldData['role']}' menjadi '{$targetUser->role}'";
            }
            
            // Perubahan cabang untuk AO/Manager
            if (in_array($targetUser->role, ['ao', 'manager'])) {
                $oldCabang = $oldData['cabang'] ?? 'kosong';
                $newCabang = $targetUser->cabang ?? 'kosong';
                if ($oldCabang !== $newCabang) {
                    $changes[] = "Cabang dari '{$oldCabang}' menjadi '{$newCabang}'";
                }
            }
            
            // Perubahan cabang binaan untuk Supervisor
            if ($targetUser->role === 'supervisor') {
                $oldBinaan = !empty($oldData['cabang_binaan']) ? implode(', ', $oldData['cabang_binaan']) : 'kosong';
                $newBinaan = !empty($targetUser->cabang_binaan) ? implode(', ', $targetUser->cabang_binaan) : 'kosong';
                if ($oldBinaan !== $newBinaan) {
                    $changes[] = "Cabang binaan dari '{$oldBinaan}' menjadi '{$newBinaan}'";
                }
            }
            
            if (!empty($changes)) {
                $description .= " - " . implode(", ", $changes);
            } else {
                $description .= " - Tidak ada perubahan data" . ($passwordChanged ? " (hanya password diubah)" : "");
            }
            
            $this->logActivity(
                'UPDATE',
                'USER',
                $description,
                $oldData,
                $targetUser->fresh()->toArray()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate',
                'data' => $targetUser->makeHidden(['password'])
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in UserController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            // Hanya admin yang bisa hapus user
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Hanya admin yang dapat menghapus user'
                ], 403);
            }
            
            $targetUser = User::findOrFail($id);
            $oldData = $targetUser->toArray();
            
            // Cegah menghapus diri sendiri
            if ($user->id == $targetUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri'
                ], 400);
            }
            
            // Cegah menghapus admin terakhir
            $adminCount = User::where('role', 'admin')->count();
            if ($targetUser->role === 'admin' && $adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus admin terakhir. Minimal harus ada 1 admin.'
                ], 400);
            }
            
            // Log activity DELETE sebelum hapus
            $cabangInfo = '';
            if (in_array($targetUser->role, ['ao', 'manager'])) {
                $cabangInfo = " - Cabang: {$targetUser->cabang}";
            } elseif ($targetUser->role === 'supervisor') {
                $cabangInfo = " - Cabang Binaan: " . implode(', ', $targetUser->cabang_binaan ?? []);
            }
            
            $this->logActivity(
                'DELETE',
                'USER',
                "Menghapus user: {$targetUser->name} (Username: {$targetUser->username}) dengan role {$targetUser->role}{$cabangInfo}",
                $oldData,
                null
            );
            
            $targetUser->delete();
            
            Log::info('User deleted successfully', [
                'deleted_user_id' => $id,
                'deleted_user_name' => $targetUser->name,
                'deleted_by' => $user->name,
                'admin_count_left' => $adminCount - 1
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User ' . $targetUser->name . ' berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get current user profile
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            
            // Tambahkan informasi viewable cabangs untuk supervisor
            $profileData = $user->makeHidden(['password'])->toArray();
            
            if ($user->isSupervisor()) {
                $profileData['viewable_cabangs'] = $user->getViewableCabangs();
                $profileData['cabang_binaan_label'] = $user->cabang_binaan_label;
            }
            
            // Log aktivitas melihat profil sendiri
            $this->logActivity(
                'VIEW_PROFILE',
                'USER',
                "Melihat profil sendiri: {$user->name}",
                null,
                ['user_id' => $user->id, 'role' => $user->role]
            );
            
            return response()->json([
                'success' => true,
                'data' => $profileData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat profil: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update current user password
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:4|different:current_password',
                'confirm_password' => 'required|string|same:new_password'
            ]);
            
            // Cek current password
            if (!Hash::check($request->current_password, $user->password)) {
                Log::warning('Failed password change attempt', [
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
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            // Log activity untuk perubahan password
            $this->logActivity(
                'UPDATE_PASSWORD',
                'USER',
                "Mengubah password sendiri untuk user: {$user->name} (Username: {$user->username})",
                $oldData,
                ['password' => '********', 'changed_at' => now()]
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
            Log::error('Error in UserController@updatePassword: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get users by role (untuk filter dropdown)
     */
    public function getByRole($role)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            if (!in_array($role, ['admin', 'manager', 'supervisor', 'ao'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak valid'
                ], 422);
            }
            
            $users = User::where('role', $role)->orderBy('name')->get();
            
            // Log aktivitas filter by role
            $this->logActivity(
                'FILTER',
                'USER',
                "Melihat daftar user dengan role: {$role}. Total: {$users->count()} user",
                null,
                ['role' => $role, 'total' => $users->count()]
            );
            
            return response()->json([
                'success' => true,
                'data' => $users->makeHidden(['password'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@getByRole: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get users by cabang (for AO listing)
     */
    public function getByCabang(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Admin, Manager, dan Supervisor bisa melihat AO berdasarkan cabang
            if (!in_array($user->role, ['admin', 'manager', 'supervisor'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $request->validate([
                'cabang' => 'required|string'
            ]);
            
            // Supervisor hanya bisa melihat cabang binaannya
            if ($user->role === 'supervisor' && !$user->canViewCabang($request->cabang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke cabang ini'
                ], 403);
            }
            
            $users = User::where('cabang', $request->cabang)
                ->where('role', 'ao')
                ->orderBy('name')
                ->get();
            
            // Log aktivitas filter by cabang
            $this->logActivity(
                'FILTER',
                'USER',
                "Melihat daftar AO di cabang: {$request->cabang}. Total: {$users->count()} AO",
                null,
                ['cabang' => $request->cabang, 'total' => $users->count()]
            );
            
            return response()->json([
                'success' => true,
                'data' => $users->makeHidden(['password'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@getByCabang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get AOs for dropdown (by supervisor's viewable cabangs)
     */
    public function getAOList(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                // Admin bisa lihat semua AO
                $aos = User::where('role', 'ao')
                    ->orderBy('cabang')
                    ->orderBy('name')
                    ->get(['id', 'name', 'cabang']);
            } elseif ($user->role === 'supervisor') {
                // Supervisor hanya lihat AO dari cabang binaan
                $viewableCabangs = $user->getViewableCabangs();
                $aos = User::where('role', 'ao')
                    ->whereIn('cabang', $viewableCabangs)
                    ->orderBy('cabang')
                    ->orderBy('name')
                    ->get(['id', 'name', 'cabang']);
            } elseif ($user->role === 'manager') {
                // Manager hanya lihat AO dari cabangnya
                $aos = User::where('role', 'ao')
                    ->where('cabang', $user->cabang)
                    ->orderBy('name')
                    ->get(['id', 'name', 'cabang']);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'data' => $aos
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@getAOList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data AO: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get supervisors list (for admin)
     */
    public function getSupervisors()
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $supervisors = User::where('role', 'supervisor')
                ->orderBy('name')
                ->get(['id', 'name', 'username', 'cabang_binaan']);
            
            // Tambahkan label untuk cabang binaan
            foreach ($supervisors as $supervisor) {
                $supervisor->cabang_binaan_label = $supervisor->cabang_binaan_label;
            }
            
            return response()->json([
                'success' => true,
                'data' => $supervisors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@getSupervisors: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data supervisor: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reset user password (admin function)
     */
    public function resetPassword(Request $request, $id)
    {
        try {
            $admin = Auth::user();
            
            // Hanya admin yang bisa reset password
            if ($admin->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Hanya admin yang dapat mereset password'
                ], 403);
            }
            
            $request->validate([
                'new_password' => 'required|string|min:4',
                'confirm_password' => 'required|string|same:new_password'
            ]);
            
            $targetUser = User::findOrFail($id);
            $oldData = ['password' => '********'];
            
            $targetUser->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            // Log activity untuk reset password oleh admin
            $this->logActivity(
                'RESET_PASSWORD',
                'USER',
                "Merest password untuk user: {$targetUser->name} (Username: {$targetUser->username}) dengan role {$targetUser->role} oleh admin: {$admin->name}",
                $oldData,
                ['password' => '********', 'reset_by' => $admin->name, 'reset_at' => now()]
            );
            
            Log::info('Password reset by admin', [
                'target_user_id' => $id,
                'target_username' => $targetUser->username,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Password untuk user {$targetUser->name} berhasil direset"
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in UserController@resetPassword: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Toggle user active status
     */
    public function toggleActive($id)
    {
        try {
            $admin = Auth::user();
            
            if ($admin->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Hanya admin yang dapat mengubah status user'
                ], 403);
            }
            
            $targetUser = User::findOrFail($id);
            
            // Cegah menonaktifkan diri sendiri
            if ($admin->id == $targetUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menonaktifkan akun sendiri'
                ], 400);
            }
            
            // Cegah menonaktifkan admin terakhir
            if ($targetUser->role === 'admin' && $targetUser->is_active && User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menonaktifkan admin terakhir yang aktif. Minimal harus ada 1 admin aktif.'
                ], 400);
            }
            
            $oldData = $targetUser->toArray();
            $newStatus = !$targetUser->is_active;
            
            $targetUser->update(['is_active' => $newStatus]);
            
            $this->logActivity(
                'TOGGLE_ACTIVE',
                'USER',
                "Mengubah status user: {$targetUser->name} (Username: {$targetUser->username}) dari " . ($oldData['is_active'] ? 'Aktif' : 'Nonaktif') . " menjadi " . ($newStatus ? 'Aktif' : 'Nonaktif'),
                $oldData,
                $targetUser->toArray()
            );
            
            Log::info('User status toggled', [
                'user_id' => $id,
                'user_name' => $targetUser->name,
                'old_status' => $oldData['is_active'],
                'new_status' => $newStatus,
                'admin_id' => $admin->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Status user berhasil diubah menjadi " . ($newStatus ? 'Aktif' : 'Nonaktif'),
                'data' => ['is_active' => $newStatus]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@toggleActive: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk delete users (admin function)
     */
    public function bulkDelete(Request $request)
    {
        try {
            $admin = Auth::user();
            
            if ($admin->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Hanya admin yang dapat menghapus user'
                ], 403);
            }
            
            $request->validate([
                'user_ids' => 'required|array|min:1',
                'user_ids.*' => 'exists:users,id'
            ]);
            
            $userIds = $request->user_ids;
            
            // Cegah menghapus diri sendiri
            if (in_array($admin->id, $userIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri dalam bulk delete'
                ], 400);
            }
            
            // Cek admin terakhir
            $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
            $adminToDelete = array_intersect($userIds, $adminIds);
            $remainingAdmins = count(array_diff($adminIds, $adminToDelete));
            
            if ($remainingAdmins < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus semua admin. Minimal harus ada 1 admin.'
                ], 400);
            }
            
            $usersToDelete = User::whereIn('id', $userIds)->get();
            $deletedCount = 0;
            $deletedNames = [];
            
            foreach ($usersToDelete as $user) {
                $oldData = $user->toArray();
                
                // Log each deletion
                $cabangInfo = '';
                if (in_array($user->role, ['ao', 'manager'])) {
                    $cabangInfo = " - Cabang: {$user->cabang}";
                } elseif ($user->role === 'supervisor') {
                    $cabangInfo = " - Cabang Binaan: " . implode(', ', $user->cabang_binaan ?? []);
                }
                
                $this->logActivity(
                    'BULK_DELETE',
                    'USER',
                    "Menghapus user: {$user->name} (Username: {$user->username}) dengan role {$user->role}{$cabangInfo} dalam operasi bulk delete",
                    $oldData,
                    null
                );
                
                $user->delete();
                $deletedCount++;
                $deletedNames[] = $user->name;
            }
            
            Log::info('Bulk delete users completed', [
                'deleted_count' => $deletedCount,
                'deleted_users' => $deletedNames,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} user",
                'data' => ['deleted_count' => $deletedCount, 'deleted_users' => $deletedNames]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in UserController@bulkDelete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export users data
     */
    public function export(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $users = User::orderBy('role')->orderBy('name')->get();
            
            // Log export activity
            $this->logActivity(
                'EXPORT',
                'USER',
                "Mengexport data user. Total: {$users->count()} user",
                null,
                ['total' => $users->count(), 'roles' => $users->groupBy('role')->map->count()]
            );
            
            $filename = 'users_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                fputcsv($file, ['ID', 'Name', 'Username', 'Role', 'Cabang', 'Cabang Binaan', 'Level', 'Status', 'Created At', 'Updated At']);
                
                foreach ($users as $user) {
                    $cabangBinaan = '';
                    if ($user->role === 'supervisor' && !empty($user->cabang_binaan)) {
                        $cabangBinaan = implode('; ', $user->cabang_binaan);
                    }
                    
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->username,
                        $user->role,
                        $user->cabang ?? '-',
                        $cabangBinaan ?: '-',
                        $user->level ?? '-',
                        isset($user->is_active) ? ($user->is_active ? 'Active' : 'Inactive') : 'Active',
                        $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '-',
                        $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '-'
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error in UserController@export: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengexport data: ' . $e->getMessage()
            ], 500);
        }
    }
}