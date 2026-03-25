@extends('layouts.app')
@section('title', 'Manage Roles')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Role <span class="red-700 italic">Management</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Define institutional roles and organizational structure.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="bg-red-700 hover:bg-red-900 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all">+ Initialize New Role</a>
    </div>

    @if(session('error'))
        <div class="bg-sunset-orange/10 border border-sunset-orange/20 text-sunset-orange px-6 py-4 rounded-xl font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="premium-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-sm uppercase tracking-[0.15em] text-slate-400 font-extrabold">
                        <th class="px-8 py-6 border-b border-slate-100">Designation</th>
                        <th class="px-8 py-6 border-b border-slate-100">Assigned Personnel</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($roles as $role)
                    <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-extrabold uppercase tracking-widest bg-red-50 text-red-800 border border-red-200 shadow-sm">
                                {{ $role->name }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-slate-500">{{ $role->users_count }} members</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ route('admin.roles.show', $role) }}" class="p-2.5 bg-slate-50 hover:bg-red-700 hover:text-white rounded-xl transition-all shadow-sm group" title="View Assigned Users">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="p-2.5 bg-slate-50 hover:bg-red-900 hover:text-white rounded-xl transition-all shadow-sm" title="Modify Designation">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.roles.clone', $role) }}" method="POST" class="inline" onsubmit="return confirm('Clone this role and its permissions?');">
                                    @csrf
                                    <button class="p-2.5 bg-slate-50 hover:bg-slate-600 hover:text-white rounded-xl transition-all shadow-sm" title="Clone Role">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this role? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-2.5 bg-slate-50 hover:bg-sunset-orange hover:text-white rounded-xl transition-all shadow-sm" title="Revoke Role">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-12 text-center text-slate-400 font-bold">No institutional roles defined yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
