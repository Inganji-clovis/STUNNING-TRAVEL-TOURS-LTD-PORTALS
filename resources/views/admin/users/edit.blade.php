@extends('layouts.app')
@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="max-w-2xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('admin.users.show', $user) }}" class="text-gray-500 hover:text-white transition-colors flex items-center space-x-2 mb-4">
            <span>← Back to Profile</span>
        </a>
        <h1 class="text-3xl font-bold">Edit User Account</h1>
        <p class="text-gray-500">Update employee details and access levels</p>
    </div>

    <div class="glass border border-gray-800 p-8 rounded-3xl">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm text-gray-400 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm text-gray-400 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm text-gray-400 mb-2">Role</label>
                    <select name="role" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all appearance-none">
                        <option value="operations" {{ $user->role === 'operations' ? 'selected' : '' }}>Operations</option>
                        <option value="sales" {{ $user->role === 'sales' ? 'selected' : '' }}>Sales</option>
                        <option value="md" {{ $user->role === 'md' ? 'selected' : '' }}>Managing Director</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>System Admin</option>
                    </select>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm text-gray-400 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" placeholder="+250..." class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">New Password (Leave blank to keep current)</label>
                <input type="password" name="password" placeholder="Min 8 characters" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all">
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 py-4 rounded-xl font-semibold shadow-lg shadow-red-600/20 transition-all">
                    Update User Account
                </button>
                <a href="{{ route('admin.users.show', $user) }}" class="px-8 py-4 bg-gray-900 border border-gray-800 rounded-xl hover:bg-gray-800 transition-all flex items-center justify-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
