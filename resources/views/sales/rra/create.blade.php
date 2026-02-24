@extends('layouts.app')
@section('title', 'Archive RRA File')

@section('content')
<div class="max-w-2xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('sales.rra-files.index') }}" class="text-gray-500 hover:text-white transition-colors flex items-center space-x-2 mb-4">
            <span>← Back to Vault</span>
        </a>
        <h1 class="text-3xl font-bold text-red-500">Archive Compliance File</h1>
        <p class="text-gray-500">Private repository for RRA/EBM/IBM documents</p>
    </div>

    <div class="glass border border-gray-800 p-8 rounded-3xl">
        <form action="{{ route('sales.rra-files.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">File Type</label>
                    <select name="type" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all appearance-none">
                        <option value="ebm">EBM Report</option>
                        <option value="ibm">IBM Statement</option>
                        <option value="rra_monthly">RRA Monthly Return</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Tax Year</label>
                    <input type="number" name="year" value="{{ date('Y') }}" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Tax Month</label>
                <select name="month" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all appearance-none">
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                        <option value="{{ $month }}" {{ date('F') == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">File Upload (.PDF or .ZIP)</label>
                <input type="file" name="file" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Description / Notes</label>
                <textarea name="description" rows="3" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-red-500 transition-all"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-red-700 hover:bg-red-900 py-4 rounded-lg text-base font-semibold text-white shadow-lg shadow-red-900/20 transition-all">
                    Archive Securely
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
