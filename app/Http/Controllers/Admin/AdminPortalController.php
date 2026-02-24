<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class AdminPortalController extends Controller
{
    private $portals = [
        'operations_portal_active' => 'Operations Portal',
        'sales_portal_active' => 'Financial Sales Portal',
        'md_portal_active' => 'Executive Suite',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->portals as $key => $name) {
            $settings[$key] = Setting::getVal($key, true);
        }
        
        $ipWhitelist = Setting::getVal('ip_whitelist', '');

        return view('admin.portals.index', compact('settings', 'ipWhitelist'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'portals' => 'nullable|array',
            'ip_whitelist' => 'nullable|string'
        ]);

        $activePortals = $data['portals'] ?? [];

        foreach ($this->portals as $key => $name) {
            $isActive = in_array($key, $activePortals);
            Setting::setVal($key, $isActive, 'portals');
        }

        Setting::setVal('ip_whitelist', $data['ip_whitelist'] ?? '', 'security');

        // Log the activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_portal_settings',
            'description' => 'Updated portal access locks and IP whitelist.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Portal access configurations have been synchronized.');
    }
}
