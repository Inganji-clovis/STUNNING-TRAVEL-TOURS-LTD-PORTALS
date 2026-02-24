@extends('layouts.app')
@section('title', 'Permission Control')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Permission <span class="red-700 italic">Control</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Assign explicit actions to institutional roles.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 px-6 py-4 rounded-xl font-bold text-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Role Selector Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-[0.2em] mb-4">Select Role</h3>
            <div class="space-y-2">
                @forelse($roles as $role)
                    <a href="{{ route('admin.permissions.index', ['role_id' => $role->id]) }}" 
                       class="block px-5 py-4 rounded-2xl transition-all border font-bold text-sm shadow-sm {{ $activeRole && $activeRole->id === $role->id ? 'bg-red-950 text-white border-red-900 shadow-xl shadow-red-900/20' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-red-300 hover:text-red-950' }}">
                        {{ $role->name }}
                    </a>
                @empty
                    <div class="p-4 bg-slate-50 rounded-xl text-center text-xs font-bold text-slate-400 border border-slate-200">
                        No roles defined yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Permissions Matrix -->
        <div class="lg:col-span-3">
            @if($activeRole)
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $activeRole->id }}">
                    
                    <div class="premium-card p-8 rounded-3xl relative overflow-hidden mb-8">
                        <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">MATRIX</div>
                        
                        <div class="mb-8 flex items-center justify-between border-b border-slate-100 pb-6 relative z-10">
                            <div>
                                <h2 class="text-xl font-extrabold text-red-950">{{ $activeRole->name }}</h2>
                                <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest mt-1">Permission Matrix</p>
                            </div>
                            <button type="submit" class="bg-red-700 hover:bg-red-900 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all text-xs uppercase tracking-widest">
                                Save Matrix
                            </button>
                        </div>

                        <div class="space-y-10 relative z-10">
                            @foreach($permissionsByGroup as $group => $permissions)
                                <div>
                                    <h3 class="text-xs font-extrabold text-red-800 uppercase tracking-[0.2em] mb-6 border-l-4 border-red-700 pl-3 py-0.5">{{ $group }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @foreach($permissions as $permission)
                                            <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-4 rounded-2xl hover:border-red-200 transition-colors group">
                                                <div>
                                                    <div class="font-bold text-sm text-red-950 capitalize">{{ str_replace('_', ' ', $permission) }}</div>
                                                    <div class="text-[0.55rem] font-extrabold text-slate-400 tracking-widest uppercase mt-0.5">{{ $permission }}</div>
                                                </div>
                                                <!-- Animated Toggle using Alpine -->
                                                <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ $activeRole->hasPermissionTo($permission) ? 'true' : 'false' }} }">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission }}" class="sr-only peer" x-model="checked">
                                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-700 shadow-inner duration-300"></div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            @else
                <div class="premium-card p-12 rounded-3xl text-center border-dashed border-2 border-slate-200 bg-slate-50/50">
                    <div class="w-16 h-16 bg-white rounded-2xl mx-auto flex items-center justify-center mb-4 text-3xl shadow-sm border border-slate-100">👈</div>
                    <h3 class="text-lg font-bold text-slate-600">Select a Role</h3>
                    <p class="text-sm text-slate-400 mt-2">Choose an institutional role from the sidebar to manage its permissions.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
