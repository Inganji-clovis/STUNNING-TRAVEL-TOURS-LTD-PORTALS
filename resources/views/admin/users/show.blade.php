@extends('layouts.app')
@section('title', 'User Profile: ' . $user->name)

@section('content')
<div class="max-w-4xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-white transition-colors flex items-center space-x-2 mb-4">
                <span>← Back to Users</span>
            </a>
            <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
            <p class="text-gray-500">System Employee Profile</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-gray-900 border border-gray-800 px-6 py-3 rounded-xl hover:bg-gray-800 transition-all">Edit Details</a>
            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                @csrf
                <button class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-semibold transition-all">
                    {{ $user->is_active ? 'Suspend Account' : 'Activate Account' }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-8">
            <div class="glass border border-gray-800 p-8 rounded-3xl">
                <h2 class="text-xl font-bold mb-6 text-red-500">Employee Information</h2>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Full Name</p>
                        <p class="text-lg">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Email Address</p>
                        <p class="text-lg">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Role / Access Level</p>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-500">
                            {{ strtoupper($user->role) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Phone Number</p>
                        <p class="text-lg">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="glass border border-gray-800 p-8 rounded-3xl">
                <h2 class="text-xl font-bold mb-6 text-red-500">Status</h2>
                <div class="flex items-center space-x-3">
                    <span class="h-3 w-3 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500 shadow-lg shadow-red-500/50' }}"></span>
                    <p class="text-xl font-bold">{{ $user->is_active ? 'Active' : 'Suspended' }}</p>
                </div>
                <p class="text-sm text-gray-500 mt-4 leading-relaxed">
                    @if($user->is_active)
                        This user has full access to the {{ $user->role }} portal.
                    @else
                        This user is currently barred from accessing the system.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
