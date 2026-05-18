<?php
// app/Traits/LogsActivity.php

namespace App\Traits;

use App\Models\LogActivity;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected function logActivity($action, $module, $description = null, $oldData = null, $newData = null)
    {
        $user = auth()->user();
        
        if (!$user) return;
        
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