@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-black/40 backdrop-blur-xl border-b border-purple-500/20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('properties.stock-sessions.index', $property) }}" 
                       class="text-purple-300 hover:text-purple-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Stock Session Details</h1>
                        <p class="text-purple-200 mt-1">{{ $property->name }}</p>
                    </div>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($session->isOpen())
                        bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                    @else
                        bg-slate-500/20 text-slate-300 border border-slate-500/30
                    @endif
                ">
                    {{ ucfirst($session->status) }}
                </span>
            </div>

            <!-- Session Info Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-black/30 border border-purple-400/20 rounded-lg p-3">
                    <p class="text-xs text-purple-300 mb-1">Session Date</p>
                    <p class="font-semibold text-white">{{ $session->session_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-black/30 border border-purple-400/20 rounded-lg p-3">
                    <p class="text-xs text-purple-300 mb-1">Staff Member</p>
                    <p class="font-semibold text-white">{{ $session->user->name }}</p>
                </div>
                <div class="bg-black/30 border border-purple-400/20 rounded-lg p-3">
                    <p class="text-xs text-purple-300 mb-1">Items Tracked</p>
                    <p class="font-semibold text-white">{{ $session->items->count() }}</p>
                </div>
                <div class="bg-black/30 border border-purple-400/20 rounded-lg p-3">
                    <p class="text-xs text-purple-300 mb-1">Closing Balance</p>
                    <p class="font-semibold {{ $session->closing_balance ? 'text-emerald-400' : 'text-purple-300' }}">
                        {{ $session->closing_balance ? '₱' . number_format($session->closing_balance, 2) : 'Pending' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Session Items Table -->
        <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black/40 border-b border-purple-400/20">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">Item Name</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Opening Qty</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Qty Used</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Adjustment</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Expected Closing</th>
                            @if($session->isClosed())
                                <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Actual Closing</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Variance</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-400/10">
                        @foreach($session->items as $item)
                            <tr class="hover:bg-black/20 transition-colors">
                                <td class="px-6 py-4 text-white font-medium">
                                    {{ $item->inventoryItem->name }}
                                </td>
                                <td class="px-6 py-4 text-right text-purple-200">
                                    {{ number_format($item->opening_quantity, 3) }}
                                </td>
                                <td class="px-6 py-4 text-right text-purple-200">
                                    {{ number_format($item->quantity_used, 3) }}
                                </td>
                                <td class="px-6 py-4 text-right {{ $item->manual_adjustment != 0 ? 'text-yellow-400' : 'text-purple-200' }}">
                                    {{ $item->manual_adjustment != 0 ? (($item->manual_adjustment > 0 ? '+' : '') . number_format($item->manual_adjustment, 3)) : '0.000' }}
                                </td>
                                <td class="px-6 py-4 text-right text-blue-300 font-medium">
                                    {{ number_format($item->expected_closing ?? 0, 3) }}
                                </td>
                                @if($session->isClosed())
                                    <td class="px-6 py-4 text-right text-white font-medium">
                                        {{ number_format($item->closing_quantity, 3) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium">
                                        <span class="{{ $item->variance >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ ($item->variance >= 0 ? '+' : '') . number_format($item->variance, 3) }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($session->isOpen())
            <div class="flex gap-4">
                <a href="{{ route('properties.stock-sessions.edit', [$property, $session]) }}"
                   class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-500/50 transition-all text-center font-medium">
                    Close Session
                </a>
                <a href="{{ route('properties.stock-sessions.index', $property) }}"
                   class="flex-1 px-6 py-3 bg-slate-600/20 text-slate-300 rounded-lg hover:bg-slate-600/40 transition-all text-center font-medium border border-slate-500/30 hover:border-slate-500/50">
                    Back to List
                </a>
            </div>
        @else
            <div class="flex gap-4">
                <a href="{{ route('properties.stock-sessions.report', [$property, $session]) }}"
                   class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-500 text-white rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition-all text-center font-medium">
                    View Report
                </a>
                <a href="{{ route('properties.stock-sessions.index', $property) }}"
                   class="flex-1 px-6 py-3 bg-slate-600/20 text-slate-300 rounded-lg hover:bg-slate-600/40 transition-all text-center font-medium border border-slate-500/30 hover:border-slate-500/50">
                    Back to List
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
