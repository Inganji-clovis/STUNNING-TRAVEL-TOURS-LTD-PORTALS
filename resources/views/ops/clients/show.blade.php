@extends('layouts.app')
@section('title', $client->name . ' - Profile')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-start">
        <div class="flex items-center space-x-6">
            <div class="h-20 w-20 bg-red-600 rounded-2xl flex items-center justify-center text-3xl font-bold">
                {{ substr($client->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-4xl font-bold">{{ $client->name }}</h1>
                <p class="text-slate-400">{{ $client->membership_type }} traveler | Member since {{ $client->created_at->format('M Y') }}</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('ops.passport.create.individual', ['client_name' => $client->name]) }}" class="bg-slate-50 border border-slate-200 px-6 py-3 rounded-xl hover:bg-slate-200 transition-all">+ Passport</a>
            <a href="{{ route('ops.invoice.create.individual', ['client_name' => $client->name]) }}" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-semibold transition-all">New Invoice</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Travel Documents -->
            <div class="glass border border-slate-200 p-8 rounded-3xl">
                <h2 class="text-xl font-bold mb-6 flex items-center space-x-2">
                    <span class="text-red-500">Travel Documents</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($client->travelDocuments->count() > 0)
                        @foreach($client->travelDocuments as $doc)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">{{ strtoupper($doc->type) }} ({{ $doc->passport_number }})</p>
                                    <p class="text-xs text-slate-400 italic">Expires: {{ $doc->expiry_date?->format('d M Y') ?? 'N/A' }}</p>
                                </div>
                                <a href="#" class="text-red-500 text-sm">View</a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-slate-400 italic">No documents uploaded yet.</p>
                    @endif
                </div>
            </div>

            <!-- Billing History -->
            <div class="glass border border-slate-200 p-8 rounded-3xl">
                <h2 class="text-xl font-bold mb-6 text-red-500">Billing History</h2>
                <div class="overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-slate-200">
                            @foreach($client->billingDocuments as $bill)
                            <tr>
                                <td class="py-4 font-medium">{{ $bill->document_number }}</td>
                                <td class="py-4 uppercase text-slate-400">{{ $bill->type }}</td>
                                <td class="py-4 text-red-500 font-semibold">{{ number_format($bill->amount) }} {{ $bill->currency }}</td>
                                <td class="py-4 text-right">
                                    <span class="px-2 py-1 rounded-md bg-white/5 {{ $bill->status === 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                                        {{ strtoupper($bill->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="glass border border-slate-200 p-8 rounded-3xl">
                <h3 class="font-bold mb-4">Contact Info</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest">Email</p>
                        <p>{{ $client->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest">Phone</p>
                        <p>{{ $client->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest">Organization</p>
                        <p>{{ $client->organization->name ?? 'Individual' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
