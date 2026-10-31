@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-black/40 backdrop-blur-xl border-b border-purple-500/20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('properties.stock-sessions.index', $property) }}" 
                       class="text-purple-300 hover:text-purple-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Stock Session Report</h1>
                        <p class="text-purple-200 mt-1">{{ $session->session_date->format('M d, Y') }}</p>
                    </div>
                </div>
                <button onclick="window.print()" class="px-4 py-2 bg-purple-600/20 text-purple-300 rounded-lg hover:bg-purple-600/40 transition-all border border-purple-500/30 hover:border-purple-500/50">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm2-8a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <p class="text-sm text-purple-300 mb-2">Total Opening Value</p>
                <p class="text-2xl font-bold text-emerald-400">₱{{ number_format($summary['total_opening_value'], 2) }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <p class="text-sm text-purple-300 mb-2">Total Closing Value</p>
                <p class="text-2xl font-bold text-blue-400">₱{{ number_format($summary['total_closing_value'], 2) }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <p class="text-sm text-purple-300 mb-2">Total Used Value</p>
                <p class="text-2xl font-bold text-orange-400">₱{{ number_format($summary['total_usage_value'], 2) }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <p class="text-sm text-purple-300 mb-2">Total Variance</p>
                <p class="text-2xl font-bold {{ $summary['total_variance_value'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ $summary['total_variance_value'] >= 0 ? '+' : '' }}₱{{ number_format($summary['total_variance_value'], 2) }}
                </p>
            </div>
        </div>

        <!-- Detailed Items Report -->
        <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl overflow-hidden mb-8">
            <div class="bg-black/40 border-b border-purple-400/20 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Detailed Items Report</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black/40 border-b border-purple-400/20">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">Item</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Opening Qty</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Unit Cost</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Opening Value</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Used</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Closing Qty</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Closing Value</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Variance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-400/10">
                        @foreach($items as $item)
                            <tr class="hover:bg-black/20 transition-colors">
                                <td class="px-6 py-4 text-white font-medium">{{ $item->inventoryItem->name }}</td>
                                <td class="px-6 py-4 text-right text-purple-200 text-sm">{{ number_format($item->opening_quantity, 3) }}</td>
                                <td class="px-6 py-4 text-right text-purple-200 text-sm">₱{{ number_format($item->unit_cost, 2) }}</td>
                                <td class="px-6 py-4 text-right text-emerald-400 font-medium text-sm">₱{{ number_format($item->opening_value, 2) }}</td>
                                <td class="px-6 py-4 text-right text-purple-200 text-sm">{{ number_format($item->quantity_used, 3) }}</td>
                                <td class="px-6 py-4 text-right text-white font-medium text-sm">{{ number_format($item->closing_quantity, 3) }}</td>
                                <td class="px-6 py-4 text-right text-blue-400 font-medium text-sm">₱{{ number_format($item->closing_value, 2) }}</td>
                                <td class="px-6 py-4 text-right font-medium text-sm">
                                    <span class="{{ $item->variance >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ ($item->variance >= 0 ? '+' : '') . number_format($item->variance, 3) }}
                                        (₱{{ ($item->variance >= 0 ? '+' : '') . number_format($item->variance * $item->unit_cost, 2) }})
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Session Info -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Session Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-purple-300">Session Date:</span>
                        <span class="text-white font-medium">{{ $session->session_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-purple-300">Staff Member:</span>
                        <span class="text-white font-medium">{{ $session->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-purple-300">Items Tracked:</span>
                        <span class="text-white font-medium">{{ $summary['total_items_count'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-purple-300">Session Status:</span>
                        <span class="text-emerald-400 font-medium">{{ ucfirst($session->status) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Variance Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-purple-300">Total Variance Value:</span>
                        <span class="font-bold {{ $session->variance >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $session->variance >= 0 ? '+' : '' }}₱{{ number_format($session->variance, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-purple-300">Variance Percentage:</span>
                        <span class="font-bold {{ $session->variance_percentage >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $session->variance_percentage >= 0 ? '+' : '' }}{{ number_format($session->variance_percentage, 2) }}%
                        </span>
                    </div>
                    @if($session->notes)
                        <div class="border-t border-purple-400/20 pt-3 mt-3">
                            <p class="text-purple-300 text-sm mb-1">Notes:</p>
                            <p class="text-white text-sm">{{ $session->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="{{ route('properties.stock-sessions.show', [$property, $session]) }}"
               class="flex-1 px-6 py-3 bg-slate-600/20 text-slate-300 rounded-lg hover:bg-slate-600/40 transition-all text-center font-medium border border-slate-500/30 hover:border-slate-500/50">
                Back to Session
            </a>
            <a href="{{ route('properties.stock-sessions.index', $property) }}"
               class="flex-1 px-6 py-3 bg-slate-600/20 text-slate-300 rounded-lg hover:bg-slate-600/40 transition-all text-center font-medium border border-slate-500/30 hover:border-slate-500/50">
                All Sessions
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        .hidden-on-print { display: none !important; }
        body { background: white; }
        .bg-gradient-to-br { background: white; }
        .text-white { color: #000; }
        .bg-white\/5 { background: #f5f5f5; border-color: #ccc; }
        .border-purple-400\/20 { border-color: #ccc; }
    }
</style>
@endsection
