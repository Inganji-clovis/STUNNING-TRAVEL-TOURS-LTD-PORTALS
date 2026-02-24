@extends('layouts.app')
@section('title', 'Archive Corporate Document')

@section('content')
<div class="max-w-2xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('md.vault.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center space-x-2 mb-4">
            <span>← Back to Vault</span>
        </a>
        <h1 class="text-3xl font-bold text-red-500 tracking-tight">Archive Corporate Document</h1>
        <p class="text-slate-400">Secure entry of company critical documentation</p>
    </div>

    <div class="glass border border-slate-200 p-8 rounded-3xl">
        <form action="{{ route('md.vault.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm text-slate-500 mb-2">Document Title</label>
                <input type="text" name="title" required placeholder="e.g. Board Resolution Q1 2026" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all">
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-2">Category</label>
                <select name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all appearance-none">
                    <option value="board">Board Resolution</option>
                    <option value="legal">Legal & Registration</option>
                    <option value="tax">Tax Compliance (Corporate)</option>
                    <option value="policy">Company Policy</option>
                    <option value="other">Other Sensitive Docs</option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-2">Secure File Upload (.PDF or .ZIP)</label>
                <input type="file" name="file" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700">
                <p class="mt-2 text-xs text-slate-400 italic">Files are stored in an encrypted vault and are only accessible by the MD and authorized executives.</p>
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-2">Summary / Purpose</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-4 rounded-xl font-semibold shadow-lg shadow-red-600/20 transition-all">
                    Archived Successfully
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
