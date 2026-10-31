@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-black/40 backdrop-blur-xl border-b border-purple-500/20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('properties.stock-sessions.show', [$property, $session]) }}" 
                   class="text-purple-300 hover:text-purple-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-white">Close Stock Session</h1>
                    <p class="text-purple-200 mt-1">{{ $session->session_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                <h4 class="font-semibold text-red-300 mb-2">Error</h4>
                <ul class="text-red-200 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('properties.stock-sessions.update', [$property, $session]) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Items to Close -->
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-black/40 border-b border-purple-400/20">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">Item Name</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Opening</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Used</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Expected</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-purple-300">Actual Closing Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-purple-400/10">
                            @foreach($session->items as $item)
                                @php
                                    $expected = $item->opening_quantity + $item->quantity_received - $item->quantity_used + $item->manual_adjustment;
                                @endphp
                                <tr class="hover:bg-black/20 transition-colors">
                                    <td class="px-6 py-4 text-white font-medium">
                                        {{ $item->inventoryItem->name }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-purple-200 text-sm">
                                        {{ number_format($item->opening_quantity, 3) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-purple-200 text-sm">
                                        {{ number_format($item->quantity_used, 3) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-blue-300 font-medium text-sm">
                                        {{ number_format($expected, 3) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <input type="number"
                                               step="0.001"
                                               min="0"
                                               name="closing_quantity[{{ $item->id }}]"
                                               value="{{ old("closing_quantity.{$item->id}", $expected) }}"
                                               placeholder="{{ number_format($expected, 3) }}"
                                               class="px-3 py-2 bg-black/20 border border-purple-400/30 rounded-lg text-white w-full text-sm focus:border-purple-400 focus:ring-1 focus:ring-purple-400 transition-all"
                                               required
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                <p class="text-sm text-blue-300">
                    <strong>Note:</strong> Enter the actual physical count for each item. The system will calculate the variance automatically.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <a href="{{ route('properties.stock-sessions.show', [$property, $session]) }}"
                   class="flex-1 px-6 py-3 bg-slate-600/20 text-slate-300 rounded-lg hover:bg-slate-600/40 transition-all text-center font-medium border border-slate-500/30 hover:border-slate-500/50">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-500/50 transition-all font-medium">
                    Confirm & Close Session
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
