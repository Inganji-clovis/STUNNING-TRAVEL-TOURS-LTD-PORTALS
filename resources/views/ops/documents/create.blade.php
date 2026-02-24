@extends('layouts.app')
@section('title', 'Upload Document')

@section('content')
<div class="max-w-2xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('ops.clients.show', $clientId ?? '') }}" class="text-slate-400 hover:text-white transition-colors flex items-center space-x-2 mb-4">
            <span>Ã¢â€ Â Back to Client Profile</span>
        </a>
        <h1 class="text-3xl font-bold text-red-500">Upload Travel Document</h1>
        <p class="text-slate-400">Securely store passports and visas</p>
    </div>

    <div class="glass border border-slate-200 p-8 rounded-3xl">
        <form action="{{ route('ops.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm text-slate-400 mb-2">Select Client</label>
                <select name="client_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all appearance-none">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $clientId == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-slate-400 mb-2">Document Type</label>
                    <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all appearance-none">
                        <option value="passport">Passport</option>
                        <option value="visa">Visa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-2">Expiry Date</label>
                    <input type="date" name="expiry_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-400 mb-2">File Upload (PDF or Image)</label>
                <input type="file" name="file" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700">
                <p class="mt-2 text-xs text-slate-400 italic">Files are encrypted and stored in private system storage.</p>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-4 rounded-xl font-semibold shadow-lg shadow-red-600/20 transition-all">
                    Upload & Secure
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
