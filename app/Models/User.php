<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'cabang',
        'cabang_binaan',
        'level'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'cabang_binaan' => 'array',
            'level' => 'integer',
        ];
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is Manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is AO (Account Officer)
     */
    public function isAO(): bool
    {
        return $this->role === 'ao';
    }

    /**
     * Check if user is Supervisor
     */
    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Cek apakah user bisa melihat data cabang tertentu
     */
    public function canViewCabang(string $cabangName): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        if ($this->isSupervisor()) {
            // Jika cabang_binaan null atau kosong, bisa lihat semua
            if (empty($this->cabang_binaan)) {
                return true;
            }
            return in_array($cabangName, $this->cabang_binaan);
        }
        
        if ($this->isManager() || $this->isAO()) {
            return $this->cabang === $cabangName;
        }
        
        return false;
    }

    /**
     * Ambil daftar cabang yang bisa dilihat
     */
    public function getViewableCabangs(): array
    {
        $allCabangs = ['Pusat', 'Kisaran', 'Perdagangan', 'Pematangsiantar', 'Sidamanik', 'Stabat'];
        
        if ($this->isAdmin()) {
            return $allCabangs;
        }
        
        if ($this->isSupervisor()) {
            if (!empty($this->cabang_binaan)) {
                return $this->cabang_binaan;
            }
            return $allCabangs;
        }
        
        if ($this->isManager() || $this->isAO()) {
            return [$this->cabang];
        }
        
        return [];
    }

    /**
     * Cek apakah user bisa melakukan approve/reject
     */
    public function canApprove(): bool
    {
        return in_array($this->role, ['manager', 'admin']);
    }

    /**
     * Cek apakah user bisa menghapus data
     */
    public function canDelete(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Cek apakah user bisa mengelola user lain
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Cek apakah user bisa input data
     */
    public function canInputData(): bool
    {
        return $this->isAO();
    }

    /**
     * Get all kunjungan created by this user
     */
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'created_by');
    }

    /**
     * Get all kunjungan approved by this user
     */
    public function approvedKunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'approved_by');
    }

    /**
     * Get kunjungan for this user based on their role
     */
    public function getAccessibleKunjungans()
    {
        if ($this->isAdmin()) {
            return Kunjungan::query();
        }
        
        if ($this->isSupervisor()) {
            // Supervisor can see all branches they supervise
            $viewableCabangs = $this->getViewableCabangs();
            return Kunjungan::whereIn('nama_cabang', $viewableCabangs);
        }
        
        if ($this->isManager()) {
            return Kunjungan::where('nama_cabang', $this->cabang);
        }
        
        if ($this->isAO()) {
            return Kunjungan::where('nama_cabang', $this->cabang)
                           ->where('nama_ao', $this->name);
        }
        
        return Kunjungan::whereRaw('1 = 0'); // Return empty query
    }

    /**
     * Scope untuk filter user by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk filter user by cabang
     */
    public function scopeByCabang($query, string $cabang)
    {
        return $query->where('cabang', $cabang);
    }

    /**
     * Get list of users by role for dropdown
     */
    public static function getListByRole(string $role)
    {
        return self::where('role', $role)
                   ->orderBy('name')
                   ->get(['id', 'name', 'username', 'cabang', 'cabang_binaan']);
    }

    /**
     * Get list of AO by cabang
     */
    public static function getAOByCabang(string $cabang)
    {
        return self::where('role', 'ao')
                   ->where('cabang', $cabang)
                   ->orderBy('name')
                   ->get(['id', 'name', 'username']);
    }

    /**
     * Get list of AO by supervisor's binaan cabangs
     */
    public static function getAOByCabangs(array $cabangs)
    {
        return self::where('role', 'ao')
                   ->whereIn('cabang', $cabangs)
                   ->orderBy('cabang')
                   ->orderBy('name')
                   ->get(['id', 'name', 'username', 'cabang']);
    }

    /**
     * Get all supervisors with their binaan cabangs
     */
    public static function getSupervisorsWithBinaan()
    {
        return self::where('role', 'supervisor')
                   ->orderBy('name')
                   ->get(['id', 'name', 'username', 'cabang_binaan']);
    }

    /**
     * Check if user can access a specific cabang
     */
    public function canAccessCabang(string $cabang): bool
    {
        return $this->canViewCabang($cabang);
    }

    /**
     * Get role label in Bahasa Indonesia
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'supervisor' => 'Supervisor',
            'manager' => 'Manager',
            'ao' => 'Account Officer',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get user's display name with role and cabang
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->isAdmin()) {
            return $this->name . ' (Admin)';
        }
        
        if ($this->isSupervisor()) {
            $cabangCount = !empty($this->cabang_binaan) ? count($this->cabang_binaan) : 'all';
            return $this->name . ' (Supervisor - ' . $cabangCount . ' cabangs)';
        }
        
        if ($this->isManager()) {
            return $this->name . ' (Manager - ' . $this->cabang . ')';
        }
        
        return $this->name . ' (AO - ' . $this->cabang . ')';
    }

    /**
     * Get cabang binaan as formatted string
     */
    public function getCabangBinaanLabelAttribute(): string
    {
        if (empty($this->cabang_binaan)) {
            return 'Semua Cabang';
        }
        
        return implode(', ', $this->cabang_binaan);
    }
}