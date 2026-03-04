@extends('layouts.app')
@section('title', 'User Profile: ' . $user->name)

@section('content')
<div class="max-w-4xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 mb-4 font-bold text-xs uppercase tracking-widest">
                <span>← Return to Personnel Directory</span>
            </a>
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-red-600 text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl shadow-red-600/20">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">{{ $user->name }}</h1>
                    <p class="text-slate-400 font-black uppercase tracking-widest text-[10px] mt-1">Institutional Profile @ {{ $user->username }}</p>
                </div>
            </div>
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
            <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 sm:flex-none px-6 py-4 bg-white border border-slate-200 text-slate-700 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                Modify Record
            </a>
            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="flex-1 sm:flex-none">
                @csrf
                <button class="w-full px-6 py-4 {{ $user->is_active ? 'bg-red-950 hover:bg-red-900' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-slate-200">
                    {{ $user->is_active ? 'Revoke Access' : 'Restore Access' }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="premium-card p-10 rounded-3xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">DATA</div>
                <h2 class="text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span> Personnel Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Full Designation</p>
                        <p class="text-lg font-extrabold text-[#334155]">{{ $user->name }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">System Identifier</p>
                        <p class="text-lg font-extrabold text-red-600 tracking-tight">{{ $user->username }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Institutional Email</p>
                        <p class="text-lg font-extrabold text-[#334155]">{{ $user->email }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Access Authority</p>
                        <span class="inline-flex px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] bg-red-50 text-red-600 border border-red-100/50">
                            {{ $user->role }}
                        </span>
                    </div>
                    <div class="space-y-1 col-span-1 md:col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact Protocol</p>
                        <p class="text-lg font-extrabold text-[#334155]">{{ $user->phone ?? 'Protocol not initialized' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="premium-card p-10 rounded-3xl border-t-[6px] border-t-red-600">
                <h2 class="text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Status Protocol</h2>
                <div class="flex items-center space-x-3 mb-6">
                    <span class="relative flex h-3 w-3">
                        @if($user->is_active)
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        @else
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 shadow-lg shadow-rose-200"></span>
                        @endif
                    </span>
                    <p class="text-2xl font-black tracking-tighter text-[#334155]">{{ $user->is_active ? 'Active' : 'Revoked' }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 italic text-xs text-slate-500 font-medium leading-relaxed">
                    @if($user->is_active)
                        "Personnel is currently authorized for institutional operations within the <b>{{ $user->role }}</b> framework."
                    @else
                        "Institutional access has been revoked. All active tokens and sessions are currently invalidated."
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
