<?php
// app/Models/LogActivity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'user_role', 'user_cabang',
        'action', 'module', 'description', 'old_data', 'new_data',
        'ip_address', 'user_agent'
    ];
    
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Scope untuk filter
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    
    public function scopeByRole($query, $role)
    {
        return $query->where('user_role', $role);
    }
}