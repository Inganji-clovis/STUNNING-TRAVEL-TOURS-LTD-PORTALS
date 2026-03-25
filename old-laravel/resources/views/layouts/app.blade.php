<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Stunning Travel') }} - @yield('title')</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#450A0A">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    serif: ['Inter', 'sans-serif'],
                    mono: ['Inter', 'sans-serif'],
                },
                fontSize: {
                    xs: ['16px', '24px'],
                    sm: ['16px', '24px'],
                    base: ['16px', '24px'],
                    lg: ['18px', '28px'],
                    xl: ['20px', '28px'],
                    '2xl': ['24px', '32px'],
                    '3xl': ['30px', '36px'],
                    '4xl': ['36px', '40px'],
                    '5xl': ['46px', '48px'],
                    '6xl': ['46px', '48px'],
                    '7xl': ['46px', '48px'],
                    '8xl': ['46px', '48px'],
                    '9xl': ['46px', '48px'],
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Inter', sans-serif !important;
        }
        body { 
            background-color: #F8FAFC;
            overflow-x: hidden;
            font-weight: 500;
        }
        .luxury-bg {
            position: fixed;
            inset: 0;
            z-index: -10;
            background-image: url('{{ asset('assets/brand/clear_plane.png') }}');
            background-size: cover;
            background-position: center;
        }
        .luxury-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-color: rgba(248, 250, 252, 0.95);
        }
        .premium-card { 
            background-color: #FFFFFF;
            border-radius: 28px;
            border: 1px solid #FECACA !important; /* Red 200 */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #F87171 !important; /* Red 400 */
        }
        .sunset-orange { color: #E67E22; }
        .bg-sunset-orange { background-color: #E67E22; }
        .shadow-premium { box-shadow: 0 10px 40px -5px rgba(15, 23, 42, 0.05); }
        
        .nav-link {
            transition: all 0.2s ease-in-out;
        }
        .nav-link:hover {
            transform: translateX(4px);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</head>
<body class="text-red-950 min-h-screen" x-data="{ sidebarOpen: false }">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-[-100%]"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300 transform"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-[-100%]"
             class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-green-50/90 backdrop-blur-md border border-green-200 text-green-800 px-8 py-4 rounded-2xl font-bold text-sm shadow-[0_10px_40px_-10px_rgba(34,197,94,0.3)] flex items-center space-x-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(!request()->routeIs('login'))
        <div class="luxury-bg"></div>
    @endif
    
    <div class="flex min-h-screen">
        @if(!request()->routeIs('login'))
            @auth
            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" 
                 x-transition.opacity 
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-red-900/20 backdrop-blur-sm z-30 lg:hidden" 
                 style="display: none;"></div>

            <!-- Sidebar -->
            <aside @mouseleave="sidebarOpen = false"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed lg:sticky top-0 left-0 z-40 w-[260px] bg-white h-screen border-r border-[#CBD5E1] p-4 flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)] overflow-hidden whitespace-nowrap transition-transform duration-300 ease-in-out lg:z-auto">
                
                <!-- Close Button (Mobile only) -->
                <button @click="sidebarOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="mb-8 flex flex-col items-center text-center mt-4">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center p-2 mb-3 border border-slate-200">
                        <img src="{{ asset('assets/brand/logo-removebg-preview.png') }}" alt="Stunning Logo" class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-sm font-extrabold text-slate-800 uppercase tracking-[0.2em]">STUNNING TRAVEL</h1>
                    <p class="text-[10px] text-slate-400 font-bold tracking-[0.25em] uppercase mt-1.5 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Management Console
                    </p>
                </div>

                <nav class="flex-1 space-y-6 overflow-y-auto pr-2 custom-scrollbar">
                    @php
                        $role = auth()->user()->role;
                        $activeClass = 'bg-red-50 text-red-700 border-l-[3px] border-red-600 font-bold';
                        $inactiveClass = 'text-slate-500 hover:bg-slate-50 hover:text-red-700 border-l-[3px] border-transparent font-semibold shadow-none transition-colors';
                    @endphp

                    <!-- Dashboard / Console -->
                    <div>
                        <div class="space-y-1">
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Admin Dashboard</span>
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/users*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">User Management</span>
                                </a>
                                <a href="{{ route('admin.roles.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/roles*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Role Management</span>
                                </a>
                                <a href="{{ route('admin.permissions.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/permissions*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Permission Control</span>
                                </a>
                                <a href="{{ route('admin.portals.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/portals*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Portal Access Settings</span>
                                </a>
                                <a href="{{ route('admin.security.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/security*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Security Settings</span>
                                </a>
                                <a href="{{ route('admin.logs.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/logs*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Activity Logs</span>
                                </a>
                                <a href="{{ route('admin.files.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/files*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">File Oversight</span>
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/settings*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Company Settings</span>
                                </a>
                                <a href="{{ route('admin.backups.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/backups*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Backup Management</span>
                                </a>
                                <a href="{{ route('admin.cloud-sync.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('admin/cloud-sync*') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                    <span class="text-sm font-black uppercase tracking-wider">Cloud Sync</span>
                                </a>
                            @endif

                            @if($role === 'md')
                                <a href="{{ route('md.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('md') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">MD Command Center</span>
                                </a>
                            @endif

                            @if($role === 'operations')
                                <a href="{{ route('ops.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('ops') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Operations Console</span>
                                </a>
                            @endif

                             @if($role === 'sales')
                                <a href="{{ route('sales.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('sales') ? $activeClass : $inactiveClass }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    <span class="text-sm font-bold uppercase tracking-wider">Financial Control</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Operations & Logistics -->
                    @if($role === 'operations')
                    <div>
                        <div class="space-y-1">
                            <a href="{{ route('ops.clients.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('ops/clients*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Clients</span>
                            </a>
                            <a href="{{ route('ops.organizations.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('ops/organizations*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Organizations</span>
                            </a>
                            <a href="{{ route('ops.passport.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('ops/passport*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Passports</span>
                            </a>
                            <a href="{{ route('ops.tickets.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('ops/tickets*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Tickets</span>
                            </a>
                            <a href="{{ route('ops.invoice.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('ops/invoice*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Invoices</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Financial Engine -->
                    @if($role === 'sales')
                    <div>
                        <div class="space-y-1">
                            <a href="{{ route('sales.cashbook.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('sales/cashbook') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Daily Cashbook</span>
                            </a>
                            <a href="{{ route('sales.reports.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('sales/reports*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Financial Reports</span>
                            </a>
                            <a href="{{ route('sales.ebm.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('sales/ebm*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">EBM Logs</span>
                            </a>
                            <a href="{{ route('sales.payments.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('sales/payments*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Payments</span>
                            </a>
                            <a href="{{ route('sales.invoices.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('sales/invoices*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Invoices</span>
                            </a>
                            <a href="{{ route('sales.tax.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('sales/tax*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Taxation</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Executive Strategy -->
                    @if($role === 'md')
                    <div>
                        <div class="space-y-1">
                            <a href="{{ route('md.activity.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('md/activity*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Global Ledger</span>
                            </a>
                            <a href="{{ route('md.employees.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('md/employees*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Employee Base</span>
                            </a>
                            <a href="{{ route('md.folders.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-r-lg {{ Request::is('md/folders*') || Request::is('md/vault*') ? $activeClass : $inactiveClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Group Repositories</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </nav>

                <div class="mt-auto border-t border-slate-100 pt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full flex items-center justify-center space-x-2 py-3 rounded-xl bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors font-bold text-[11px] uppercase tracking-widest group border border-slate-100 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="group-hover:-translate-x-1 transition-transform tracking-[0.2em]">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>
            @endauth
        @endif

        <main class="{{ request()->routeIs('login') ? 'flex-1 w-full overflow-y-auto' : 'flex-1 flex flex-col w-full min-h-screen relative z-10' }}">
            @if(!request()->routeIs('login'))
                @auth
                <!-- Topbar with Hamburger Menu -->
                <header class="w-full bg-white/95 backdrop-blur-md border-b border-[#CBD5E1] p-4 sticky top-0 z-20 flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 mr-4 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="text-red-700 font-bold tracking-wider text-sm uppercase">STUNNING TRAVEL PORTAL</div>
                </header>
                @endauth
            @endif
            
            <div class="{{ request()->routeIs('login') ? 'h-full' : 'p-6 lg:p-8 max-w-7xl mx-auto w-full' }}">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
    
    <!-- Universal File Upload Preview System -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.addEventListener('change', (e) => {
                if (e.target && e.target.type === 'file') {
                    const files = e.target.files;
                    if (files && files.length > 0) {
                        // Find the nearest encompassing logical container to attach the preview node to
                        let container = e.target.closest('div.relative, div.group, label.group');
                        let isDashedArea = true;
                        
                        if (!container) {
                            container = e.target.parentElement;
                            isDashedArea = false;
                        }
                        
                        // Locate or create the universal preview node securely
                        let previewEl = container.nextElementSibling;
                        if (!previewEl || !previewEl.classList.contains('universal-file-preview')) {
                            previewEl = document.createElement('div');
                            previewEl.className = 'universal-file-preview mt-3 p-3 bg-red-50 border border-red-200 rounded-xl flex flex-col space-y-2 shadow-inner transition-all duration-500 animate-in slide-in-from-top-2 fade-in w-full';
                            container.parentNode.insertBefore(previewEl, container.nextSibling);
                        }
                        
                        previewEl.innerHTML = '';
                        
                        // Handle single or multiple file aggregations iteratively
                        Array.from(files).forEach(file => {
                            const isImage = file.type.startsWith('image/');
                            const sizeKB = (file.size / 1024).toFixed(1);
                            
                            const fileWrapper = document.createElement('div');
                            fileWrapper.className = 'flex items-center space-x-3 w-full p-2 bg-white rounded-lg shadow-sm border border-red-100';
                            
                            let iconHtml = `<div class="w-10 h-10 shrink-0 bg-red-50 text-red-700 rounded-lg flex items-center justify-center border border-red-200 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>`;
                            
                            if (isImage) {
                                const objectUrl = URL.createObjectURL(file);
                                iconHtml = `<div class="w-12 h-12 shrink-0 rounded-lg overflow-hidden border-2 border-red-200 shadow-[0_4px_10px_-2px_rgba(185,28,28,0.3)] bg-white relative"><div class="absolute inset-0 bg-black/5 z-10"></div><img src="${objectUrl}" class="w-full h-full object-cover"></div>`;
                            }
                            
                            fileWrapper.innerHTML = `
                                ${iconHtml}
                                <div class="flex-1 min-w-0 overflow-hidden text-left py-1">
                                    <div class="text-sm font-bold text-red-950 truncate">${file.name}</div>
                                    <div class="text-sm font-black text-red-700 mt-1 uppercase tracking-widest">${sizeKB} KB &bull; ${isImage ? 'IMAGE' : 'DOCUMENT'} DATA</div>
                                </div>
                                <div class="shrink-0 pr-2">
                                    <div class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            `;
                            
                            previewEl.appendChild(fileWrapper);
                        });
                        
                        // Apply soft feedback logic to the dashed upload box
                        if (isDashedArea) {
                            const promptEl = container.querySelector('.border-dashed');
                            if (promptEl) {
                                promptEl.classList.add('bg-red-50/50', 'border-red-300', 'shadow-[inset_0_2px_15px_-5px_rgba(220,38,38,0.1)]');
                                promptEl.classList.remove('bg-slate-50', 'border-slate-200');
                                const textEl = promptEl.querySelector('p');
                                if(textEl) {
                                    textEl.innerHTML = `<span class="text-red-700 font-bold">Successfully Attached.</span><br><span class="text-xs opacity-70">Click to replace file hierarchy</span>`;
                                }
                                const iconEl = promptEl.querySelector('span.text-3xl');
                                if(iconEl) iconEl.innerHTML = '🛡️';
                            }
                        }
                    } else {
                        // Revert if file dialogue was cancelled
                        let prevPreview = e.target.closest('div.relative, div.group, label.group')?.nextElementSibling;
                        if(prevPreview && prevPreview.classList.contains('universal-file-preview')) {
                            prevPreview.remove();
                        }
                    }
                }
            });
        });
    </script>
    
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.log('Service Worker registration failed', err));
            });
        }
    </script>
</body>
</html>
