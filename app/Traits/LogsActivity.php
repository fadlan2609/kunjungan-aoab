<?php
// app/Traits/LogsActivity.php

namespace App\Traits;

use App\Models\LogActivity;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;  // TAMBAHKAN INI

trait LogsActivity
{
    protected function logActivity($action, $module, $description = null, $oldData = null, $newData = null)
    {
        $user = auth()->user();
        
        if (!$user) return;
        
        // TAMBAHKAN DEBUG LOG INI
        Log::info('LogActivity Debug:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,  // LIHAT APA YANG TERSIMPAN
            'action' => $action,
            'module' => $module
        ]);
        
        LogActivity::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'user_cabang' => $user->cabang,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }
}