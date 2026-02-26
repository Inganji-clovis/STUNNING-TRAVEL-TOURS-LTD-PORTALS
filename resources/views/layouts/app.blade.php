<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Stunning Travel') }} - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    sans: ['"Times New Roman"', 'Times', 'serif'],
                    serif: ['"Times New Roman"', 'Times', 'serif'],
                    mono: ['"Times New Roman"', 'Times', 'serif'],
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
            font-family: 'Times New Roman', Times, serif !important;
        }
        body { 
            background-color: #0F172A; /* Deep slate background to contrast with glass */
            overflow-x: hidden;
        }
        .luxury-bg {
            position: fixed;
            inset: 0;
            z-index: -10;
            background-image: url('{{ asset('assets/brand/clear_plane.png') }}');
            background-size: cover;
            background-position: center;
            filter: brightness(0.4) saturate(1.2); /* Muted but rich */
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .glass-dark {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .premium-card { 
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 40px -15px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px -20px rgba(15, 23, 42, 0.1);
        }
        .sunset-orange { color: #E67E22; }
        .bg-sunset-orange { background-color: #E67E22; }
        .shadow-premium { box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08); }
        
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
    
    <div class="flex h-screen overflow-hidden">
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
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-40 w-64 bg-red-950/40 backdrop-blur-xl h-screen border-r border-white/20 p-4 flex flex-col shadow-2xl overflow-hidden whitespace-nowrap transition-transform duration-300 ease-in-out">
                
                <!-- Close Button (Mobile only) -->
                <button @click="sidebarOpen = false" class="absolute top-4 right-4 text-white/70 hover:text-white lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="mb-8 flex flex-col items-center text-center mt-4">
                    <div class="w-10 h-10 bg-white rounded-xl shadow-md flex items-center justify-center p-1.5 mb-3 border border-red-200">
                        <img src="{{ asset('assets/brand/logo-removebg-preview.png') }}" alt="Stunning Logo" class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-sm font-extrabold text-white uppercase tracking-[0.2em]">STUNNING TRAVEL</h1>
                    <p class="text-sm text-red-100 font-bold tracking-[0.3em] uppercase opacity-90 mt-1">Management Console</p>
                </div>

                <nav class="flex-1 space-y-6 overflow-y-auto pr-2 custom-scrollbar">
                    @php
                        $role = auth()->user()->role;
                    @endphp

                    <!-- Dashboard / Console -->
                    <div>
                        <div class="px-3 text-sm font-extrabold text-red-200 uppercase tracking-[0.2em] mb-2">Main Console</div>
                        <div class="space-y-1">
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Admin Dashboard</span>
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/users*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">User Management</span>
                                </a>
                                <a href="{{ route('admin.roles.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/roles*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Role Management</span>
                                </a>
                                <a href="{{ route('admin.permissions.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/permissions*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Permission Control</span>
                                </a>
                                <a href="{{ route('admin.portals.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/portals*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Portal Access Settings</span>
                                </a>
                                <a href="{{ route('admin.security.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/security*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Security Settings</span>
                                </a>
                                <a href="{{ route('admin.logs.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/logs*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Activity Logs</span>
                                </a>
                                <a href="{{ route('admin.files.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/files*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">File Oversight</span>
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/settings*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Company Settings</span>
                                </a>
                                <a href="{{ route('admin.backups.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/backups*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Backup Management</span>
                                </a>
                                <a href="{{ route('admin.cloud-sync.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('admin/cloud-sync*') ? 'bg-white text-red-700 shadow-sm' : 'text-emerald-300 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-black uppercase tracking-wider flex items-center gap-2">☁️ Cloud Sync</span>
                                </a>
                            @endif

                            @if($role === 'md')
                                <a href="{{ route('md.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('md') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">MD Dashboard</span>
                                </a>
                            @endif

                            @if($role === 'operations')
                                <a href="{{ route('ops.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('ops') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Operations Dashboard</span>
                                </a>
                            @endif

                             @if($role === 'sales')
                                <a href="{{ route('sales.dashboard') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('sales') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                    <span class="text-sm font-bold uppercase tracking-wider">Sales Dashboard</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Operations & Logistics -->
                    @if($role === 'operations')
                    <div>
                        <div class="px-3 text-sm font-extrabold text-red-200 uppercase tracking-[0.2em] mb-2">Operations Portal</div>
                        <div class="space-y-1">
                            <a href="{{ route('ops.clients.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('ops/clients*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Clients</span>
                            </a>
                            <a href="{{ route('ops.organizations.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('ops/organizations*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Organizations</span>
                            </a>
                            <a href="{{ route('ops.passport.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('ops/passport*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Passports</span>
                            </a>
                            <a href="{{ route('ops.tickets.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('ops/tickets*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Tickets</span>
                            </a>
                            <a href="{{ route('ops.invoice.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('ops/invoice*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Invoices</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Financial Engine -->
                    @if($role === 'sales')
                    <div>
                        <div class="px-3 text-sm font-extrabold text-red-200 uppercase tracking-[0.2em] mb-2">Financial Unit</div>
                        <div class="space-y-1">
                            <a href="{{ route('sales.ebm.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('sales/ebm*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">EBM Logs</span>
                            </a>
                            <a href="{{ route('sales.payments.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('sales/payments*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Payments</span>
                            </a>
                            <a href="{{ route('sales.invoices.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('sales/invoices*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Invoices</span>
                            </a>
                            <a href="{{ route('sales.tax.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('sales/tax*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Taxation</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Executive Strategy -->
                    @if($role === 'md')
                    <div>
                        <div class="px-3 text-sm font-extrabold text-red-200 uppercase tracking-[0.2em] mb-2">Executive Suite</div>
                        <div class="space-y-1">
                            <a href="{{ route('md.activity.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('md/activity*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Global Ledger</span>
                            </a>
                            <a href="{{ route('md.employees.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('md/employees*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Employee Base</span>
                            </a>
                            <a href="{{ route('md.folders.index') }}" class="nav-link flex items-center space-x-3 py-2 px-3 rounded-lg {{ Request::is('md/folders*') || Request::is('md/vault*') ? 'bg-white text-red-700 shadow-sm' : 'text-red-50 hover:bg-white/10 font-medium' }}">
                                <span class="text-sm font-bold uppercase tracking-wider">Group Repositories</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </nav>

                <div class="mt-auto border-t border-red-500/50 pt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full flex items-center justify-center space-x-2 py-2.5 rounded-lg bg-red-800 text-white hover:bg-white hover:text-red-700 transition-all font-bold text-sm uppercase tracking-widest border border-red-600 group">
                            <span class="group-hover:translate-x-1 transition-transform">Terminate Session</span>
                        </button>
                    </form>
                </div>
            </aside>
            @endauth
        @endif

        <main class="{{ request()->routeIs('login') ? 'flex-1 w-full overflow-y-auto' : 'flex-1 flex flex-col w-full h-screen overflow-y-auto w-full relative z-10' }}">
            @if(!request()->routeIs('login'))
                @auth
                <!-- Topbar with Hamburger Menu -->
                <header class="w-full bg-white/95 backdrop-blur-md border-b border-red-100 p-4 sticky top-0 z-20 flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 mr-4 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
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
</body>
</html>
