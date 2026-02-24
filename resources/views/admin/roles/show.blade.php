@extends('layouts.app')
@section('title', 'Role Details')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 font-bold text-xs uppercase tracking-widest">
            <span>← Return to Role Management</span>
        </a>
    </div>

    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Role <span class="red-700 italic">Analytics</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Personnel assigned to the <span class="text-red-700 font-bold">{{ $role->name }}</span> designation.</p>
        </div>
        <a href="{{ route('admin.roles.edit', $role) }}" class="bg-red-50 hover:bg-red-100 text-red-800 border border-red-200 px-8 py-3.5 rounded-xl font-bold transition-all shadow-sm">Modify Role</a>
    </div>

    <div class="premium-card rounded-3xl overflow-hidden mt-8">
        <div class="p-6 border-b border-slate-100/50 bg-slate-50/50">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-[0.2em]">Assigned Personnel Registry</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-[0.65rem] uppercase tracking-[0.15em] text-slate-400 font-extrabold">
                        <th class="px-8 py-4 border-b border-slate-100">Personnel Profile</th>
                        <th class="px-8 py-4 border-b border-slate-100 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                        <td class="px-8 py-4">
                            <div class="font-bold text-red-950 group-hover:text-red-700 transition-colors">{{ $user->name }}</div>
                            <div class="text-[0.65rem] font-extrabold text-slate-400 tracking-widest mt-1">{{ $user->email }}</div>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <a href="{{ route('admin.users.show', $user) }}" class="p-2 bg-slate-50 hover:bg-red-700 hover:text-white rounded-lg transition-all shadow-sm inline-flex items-center text-xs font-bold uppercase tracking-widest text-slate-500" title="Profile Analytics">
                                Inspect
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-8 py-12 text-center text-slate-400 font-bold">No personnel currently hold this designation.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
