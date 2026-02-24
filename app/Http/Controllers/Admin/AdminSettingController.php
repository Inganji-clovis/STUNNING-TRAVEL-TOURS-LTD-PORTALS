<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    private $companySettings = [
        'company_name' => 'Stunning Travel & Tours Ltd',
        'support_email' => 'support@stunningrw.com',
        'contact_phone' => '+250 788 123 456',
        'default_currency' => 'RWF',
        'timezone' => 'Africa/Kigali',
        'company_logo' => 'images/logo.png', // Or wherever default is
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->companySettings as $key => $default) {
            $settings[$key] = Setting::getVal($key, $default);
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'support_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'default_currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('company', 'public');
            Setting::setVal('company_logo', $path, 'company');
        }

        foreach (['company_name', 'support_email', 'contact_phone', 'default_currency', 'timezone'] as $key) {
            Setting::setVal($key, $data[$key], 'company');
        }

        // Log the activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_company_settings',
            'description' => 'Updated the organizational profile and localization settings.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Corporate profile updated successfully.');
    }
}
