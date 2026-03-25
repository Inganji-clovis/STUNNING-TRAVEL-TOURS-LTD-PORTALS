<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    /**
     * Log a standardized activity.
     *
     * @param string $portal Division (e.g. 'Sales', 'Operations', 'Admin')
     * @param string $module Specific module (e.g. 'Tickets', 'EBM Vault')
     * @param string $action CRUD action (e.g. 'Create', 'Update', 'Delete', 'Approve')
     * @param string $reference Record reference (e.g. 'PNR-123', 'John Doe')
     * @param string|null $details Extra granular description
     * @return \App\Models\ActivityLog
     */
    public static function log($portal, $module, $action, $reference, $details = null)
    {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $details,
            'properties' => [
                'portal' => $portal,
                'module' => $module,
                'reference' => $reference
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
