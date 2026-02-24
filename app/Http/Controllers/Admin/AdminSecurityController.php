<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class AdminSecurityController extends Controller
{
    private $securityConfig = [
        'require_2fa' => false,
        'auto_lock_inactive' => true,
        'force_password_reset' => false,
        'complex_passwords' => true,
        'session_timeout' => 120, // minutes
        'max_login_attempts' => 5
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->securityConfig as $key => $default) {
            $settings[$key] = Setting::getVal($key, $default);
        }

        return view('admin.security.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'settings' => 'nullable|array',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:1|max:20'
        ]);

        $activeSettings = $data['settings'] ?? [];

        $booleanKeys = ['require_2fa', 'auto_lock_inactive', 'force_password_reset', 'complex_passwords'];
        
        foreach ($booleanKeys as $key) {
            $isActive = in_array($key, $activeSettings);
            Setting::setVal($key, $isActive, 'security');
        }

        Setting::setVal('session_timeout', $data['session_timeout'], 'security');
        Setting::setVal('max_login_attempts', $data['max_login_attempts'], 'security');

        // Log the activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_security_settings',
            'description' => 'Updated system security parameters and timeouts.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Security configuration successfully applied.');
    }
}
