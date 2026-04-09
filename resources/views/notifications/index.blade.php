@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Account</p>
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tight">Notifications</h1>
                <p class="text-slate-400 text-sm font-medium mt-1">Updates on your ads, bookings, and account.</p>
            </div>

            @if ($notifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.readAll') }}" class="mt-2 shrink-0">
                    @csrf
                    <button type="submit"
                        class="text-[11px] font-black text-slate-500 hover:text-slate-800 uppercase tracking-widest transition-all">
                        Mark all read
                    </button>
                </form>
            @endif
        </div>

        {{-- Notification list --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            @if ($notifications->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <p class="text-slate-400 font-bold text-sm">No notifications yet.</p>
                    <p class="text-xs text-slate-300 mt-1">Updates on your ads, bookings, and account will appear here.</p>
                </div>

            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($notifications as $notification)
                        @php
                            [$bgClass, $textClass] = $notification->colourClasses();
                        @endphp

                        <div class="flex items-start gap-4 px-6 py-4 {{ $notification->isUnread() ? 'bg-emerald-50/40' : '' }} hover:bg-slate-50 transition-all">

                            {{-- Icon dot --}}
                            <div class="mt-0.5 shrink-0 w-8 h-8 rounded-lg {{ $bgClass }} flex items-center justify-center">
                                @if ($notification->iconType() === 'check')
                                    <svg class="w-4 h-4 {{ $textClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                @elseif ($notification->iconType() === 'cross')
                                    <svg class="w-4 h-4 {{ $textClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 {{ $textClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-[13px] font-bold text-slate-900 leading-snug">
                                        {{ $notification->title }}
                                        @if ($notification->isUnread())
                                            <span class="ml-1.5 inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 align-middle"></span>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-medium shrink-0 mt-0.5">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if ($notification->body)
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $notification->body }}</p>
                                @endif
                                @if ($notification->url)
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-black text-emerald-600 hover:underline uppercase tracking-widest mt-1.5">
                                            View →
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection