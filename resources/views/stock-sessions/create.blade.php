@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-black/40 backdrop-blur-xl border-b border-purple-500/20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('properties.stock-sessions.index', $property) }}" 
                   class="text-purple-300 hover:text-purple-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-white">Open New Stock Session</h1>
                    <p class="text-purple-200 mt-1">{{ $property->name }}</p>
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

        <form method="POST" action="{{ route('properties.stock-sessions.store', $property) }}" class="space-y-6">
            @csrf

            <!-- Session Date -->
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <label for="session_date" class="block text-sm font-medium text-white mb-3">Session Date</label>
                <input type="date" 
                       id="session_date"
                       name="session_date" 
                       value="{{ old('session_date', now()->toDateString()) }}"
                       required
                       class="w-full px-4 py-2 bg-black/20 border border-purple-400/30 rounded-lg text-white placeholder-purple-300/50 focus:border-purple-400 focus:ring-1 focus:ring-purple-400 transition-all"
                />
            </div>

            <!-- Inventory Items -->
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <h2 class="text-xl font-semibold text-white mb-2">Current Stock on Hand</h2>
                <p class="text-sm text-purple-300 mb-4">Review the current stock levels below. You can adjust quantities if needed before opening the session.</p>
                
                @if($items->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-black/30 border-b border-purple-400/20">
                                <tr>
                                    <th class="px-4 py-3 text-left text-purple-300 font-semibold">Item Name</th>
                                    <th class="px-4 py-3 text-left text-purple-300 font-semibold">SKU</th>
                                    <th class="px-4 py-3 text-right text-purple-300 font-semibold">Current Qty</th>
                                    <th class="px-4 py-3 text-right text-purple-300 font-semibold">Unit Cost</th>
                                    <th class="px-4 py-3 text-center text-purple-300 font-semibold">Record?</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-purple-400/10">
                                @foreach($items as $item)
                                    <tr class="hover:bg-black/20 transition-colors">
                                        <td class="px-4 py-3 text-white">{{ $item->name }}</td>
                                        <td class="px-4 py-3 text-purple-300">{{ $item->sku ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-emerald-400">
                                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                                            <input type="number" 
                                                   step="0.001"
                                                   min="0"
                                                   name="items[{{ $item->id }}][opening_quantity]"
                                                   value="{{ $item->current_quantity ?? 0 }}"
                                                   class="w-20 px-2 py-1 bg-black/40 border border-emerald-500/30 rounded text-right text-emerald-400 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all"
                                            />
                                        </td>
                                        <td class="px-4 py-3 text-right text-amber-300">₱{{ number_format($item->unit_cost, 2) }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <label class="flex items-center justify-center">
                                                <input type="checkbox" 
                                                       name="items[{{ $item->id }}][include]"
                                                       value="1"
                                                       checked
                                                       class="rounded border-purple-400/30 bg-black/20 text-emerald-600"
                                                />
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-purple-300/50 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-purple-300">No inventory items found. Add items in Inventory management.</p>
                    </div>
                @endif
            </div>

            <!-- Notes -->
            <div class="bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl p-6">
                <label for="notes" class="block text-sm font-medium text-white mb-3">Notes (Optional)</label>
                <textarea id="notes"
                          name="notes"
                          rows="3"
                          placeholder="Add any additional notes..."
                          class="w-full px-4 py-2 bg-black/20 border border-purple-400/30 rounded-lg text-white placeholder-purple-300/50 focus:border-purple-400 focus:ring-1 focus:ring-purple-400 transition-all resize-none"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <a href="{{ route('properties.stock-sessions.index', $property) }}"
                   class="flex-1 px-6 py-3 bg-slate-600/20 text-slate-300 rounded-lg hover:bg-slate-600/40 transition-all text-center font-medium border border-slate-500/30 hover:border-slate-500/50">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-500 text-white rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition-all font-medium">
                    Open Session
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
