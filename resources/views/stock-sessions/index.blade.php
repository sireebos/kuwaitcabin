@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-black/40 backdrop-blur-xl border-b border-purple-500/20 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Stock Sessions</h1>
                    <p class="text-purple-200 mt-1">{{ $property->name }}</p>
                </div>
                <a href="{{ route('properties.stock-sessions.create', $property) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-500 text-white rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Session
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($sessions->count() > 0)
            <div class="grid gap-6">
                @foreach($sessions as $session)
                    <div class="group bg-white/5 backdrop-blur-md border border-purple-400/20 rounded-xl hover:border-purple-400/50 transition-all duration-200 overflow-hidden hover:shadow-lg hover:shadow-purple-500/10">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-xl font-semibold text-white">
                                            {{ $session->session_date->format('M d, Y') }}
                                        </h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($session->isOpen())
                                                bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                            @else
                                                bg-slate-500/20 text-slate-300 border border-slate-500/30
                                            @endif
                                        ">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </div>
                                    <p class="text-purple-300 text-sm">By {{ $session->user->name }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-emerald-400">
                                        {{ $session->closing_balance ? '₱' . number_format($session->closing_balance, 2) : 'N/A' }}
                                    </div>
                                    <p class="text-xs text-purple-300">Closing Balance</p>
                                </div>
                            </div>

                            @if($session->isClosed() && $session->variance !== null)
                                <div class="grid grid-cols-3 gap-4 mb-4 pt-4 border-t border-purple-400/10">
                                    <div>
                                        <p class="text-xs text-purple-300 mb-1">Variance</p>
                                        <p class="text-lg font-semibold {{ $session->variance >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ $session->variance >= 0 ? '+' : '' }}₱{{ number_format($session->variance, 2) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-purple-300 mb-1">Variance %</p>
                                        <p class="text-lg font-semibold {{ $session->variance_percentage >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ $session->variance_percentage >= 0 ? '+' : '' }}{{ number_format($session->variance_percentage, 2) }}%
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-purple-300 mb-1">Items Tracked</p>
                                        <p class="text-lg font-semibold text-blue-400">
                                            {{ $session->items_count ?? $session->items->count() }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-2 pt-4 border-t border-purple-400/10">
                                <a href="{{ route('properties.stock-sessions.show', [$property, $session]) }}"
                                   class="flex-1 px-4 py-2 bg-blue-600/20 text-blue-300 rounded-lg hover:bg-blue-600/40 transition-all text-sm font-medium border border-blue-500/30 hover:border-blue-500/50">
                                    View Details
                                </a>
                                @if($session->isClosed())
                                    <a href="{{ route('properties.stock-sessions.report', [$property, $session]) }}"
                                       class="flex-1 px-4 py-2 bg-purple-600/20 text-purple-300 rounded-lg hover:bg-purple-600/40 transition-all text-sm font-medium border border-purple-500/30 hover:border-purple-500/50">
                                        Report
                                    </a>
                                @else
                                    <a href="{{ route('properties.stock-sessions.edit', [$property, $session]) }}"
                                       class="flex-1 px-4 py-2 bg-emerald-600/20 text-emerald-300 rounded-lg hover:bg-emerald-600/40 transition-all text-sm font-medium border border-emerald-500/30 hover:border-emerald-500/50">
                                        Close Session
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $sessions->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-purple-400/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No Stock Sessions Yet</h3>
                <p class="text-purple-300 mb-6">Start tracking your inventory by opening a new session</p>
                <a href="{{ route('properties.stock-sessions.create', $property) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-500 text-white rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Open First Session
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
