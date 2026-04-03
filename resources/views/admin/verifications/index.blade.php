@extends('admin.layout')
@section('title', 'Verifications')
@section('page-title', 'Account Verifications')

@section('content')

{{-- Stats row --}}
@php
    $totalPending = $sellersPending->count() + $businessesPending->count() + $evStationsPending->count();
    $totalDone    = $sellersAll->count() + $businessesAll->count() + $evStationsAll->count();
    $totalApproved = $sellersAll->where('status','approved')->count() + $businessesAll->where('status','approved')->count() + $evStationsAll->where('status','approved')->count();
    $totalRejected = $sellersAll->where('status','rejected')->count() + $businessesAll->where('status','rejected')->count() + $evStationsAll->where('status','rejected')->count();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-amber-500">{{ $totalPending }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Awaiting Review</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-emerald-500">{{ $totalApproved }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Approved</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-red-500">{{ $totalRejected }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Rejected</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-gray-700">{{ $totalDone }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Total Reviewed</div>
    </div>
</div>

{{-- ── PENDING SELLERS ──────────────────────────────────────────────── --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Pending Sellers</h2>
        @if ($sellersPending->count() > 0)
            <span class="text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                {{ $sellersPending->count() }} pending
            </span>
        @endif
    </div>

    @if ($sellersPending->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No pending seller verifications</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($sellersPending as $v)
                <div class="bg-white border border-gray-200 rounded-2xl p-5" id="seller-{{ $v->id }}">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                        {{-- Info --}}
                        <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Account</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $v->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Full Name</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->contact }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Submitted</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $v->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            {{-- View document --}}
                            <a href="{{ route('admin.verifications.document', ['type' => 'seller', 'id' => $v->id]) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View ID
                            </a>

                            {{-- Approve --}}
                            <form method="POST" action="{{ route('admin.verifications.seller.approve', $v->id) }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            {{-- Reject (toggle) --}}
                            <button onclick="toggleRejectForm('reject-seller-{{ $v->id }}')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reject
                            </button>
                        </div>
                    </div>

                    {{-- Reject reason form (hidden by default) --}}
                    <div id="reject-seller-{{ $v->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('admin.verifications.seller.reject', $v->id) }}" class="flex gap-3">
                            @csrf
                            <input type="text" name="reason" required placeholder="Enter reason for rejection..."
                                class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red-400 transition-all">
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all shrink-0">
                                Confirm Reject
                            </button>
                            <button type="button" onclick="toggleRejectForm('reject-seller-{{ $v->id }}')"
                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all shrink-0">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── PENDING BUSINESSES ───────────────────────────────────────────── --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Pending Businesses</h2>
        @if ($businessesPending->count() > 0)
            <span class="text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                {{ $businessesPending->count() }} pending
            </span>
        @endif
    </div>

    @if ($businessesPending->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No pending business verifications</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($businessesPending as $v)
                <div class="bg-white border border-gray-200 rounded-2xl p-5">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                        <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Account</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $v->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Business Name</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->business_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->contact }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Submitted</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $v->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('admin.verifications.document', ['type' => 'business', 'id' => $v->id]) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Doc
                            </a>

                            <form method="POST" action="{{ route('admin.verifications.business.approve', $v->id) }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            <button onclick="toggleRejectForm('reject-business-{{ $v->id }}')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reject
                            </button>
                        </div>
                    </div>

                    <div id="reject-business-{{ $v->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('admin.verifications.business.reject', $v->id) }}" class="flex gap-3">
                            @csrf
                            <input type="text" name="reason" required placeholder="Enter reason for rejection..."
                                class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red-400 transition-all">
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all shrink-0">
                                Confirm Reject
                            </button>
                            <button type="button" onclick="toggleRejectForm('reject-business-{{ $v->id }}')"
                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all shrink-0">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── PENDING EV STATIONS ─────────────────────────────────────────── --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Pending EV Stations</h2>
        @if ($evStationsPending->count() > 0)
            <span class="text-[10px] font-black bg-cyan-100 text-cyan-700 border border-cyan-200 px-2 py-0.5 rounded-full">
                {{ $evStationsPending->count() }} pending
            </span>
        @endif
    </div>

    @if ($evStationsPending->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No pending station verifications</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($evStationsPending as $v)
                <div class="bg-white border border-gray-200 rounded-2xl p-5" id="ev-{{ $v->id }}">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                        {{-- Info --}}
                        <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Provider</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $v->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Station Name</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->station_name }}</p>
                                <p class="text-xs text-gray-400">{{ $v->location_address }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact/License</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->contact }}</p>
                                <p class="text-xs text-gray-400">Reg: {{ $v->registration_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Submitted</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $v->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $v->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('admin.verifications.document', ['type' => 'ev_station', 'id' => $v->id]) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                View License
                            </a>

                            <form method="POST" action="{{ route('admin.verifications.ev.approve', $v->id) }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                    Approve
                                </button>
                            </form>

                            <button onclick="toggleRejectForm('reject-ev-{{ $v->id }}')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                Reject
                            </button>
                        </div>
                    </div>

                    {{-- Reject reason form --}}
                    <div id="reject-ev-{{ $v->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('admin.verifications.ev.reject', $v->id) }}" class="flex gap-3">
                            @csrf
                            <input type="text" name="reason" required placeholder="Reason for station rejection..."
                                class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red-400 transition-all">
                            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg text-xs font-black uppercase">Confirm</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── REVIEWED HISTORY ─────────────────────────────────────────────── --}}
@if ($sellersAll->count() > 0 || $businessesAll->count() > 0)
<div>
    <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest mb-4">Review History</h2>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">User</th>
                    <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                    <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Details</th>
                    <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reviewed</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($sellersAll as $v)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-bold text-gray-800">{{ $v->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $v->user->email }}</div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Seller</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-xs font-bold text-gray-700">{{ $v->full_name }}</div>
                        <div class="text-xs text-gray-400">{{ $v->contact }}</div>
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($v->status === 'approved')
                            <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Approved</span>
                        @else
                            <span class="text-[10px] font-black bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Rejected</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-400">{{ $v->updated_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.verifications.document', ['type' => 'seller', 'id' => $v->id]) }}"
                            target="_blank"
                            class="text-xs font-bold text-indigo-600 hover:underline">View ID</a>
                    </td>
                </tr>
                @endforeach

                @foreach ($businessesAll as $v)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-bold text-gray-800">{{ $v->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $v->user->email }}</div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-[10px] font-black bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Business</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-xs font-bold text-gray-700">{{ $v->business_name }}</div>
                        <div class="text-xs text-gray-400">{{ $v->contact }}</div>
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($v->status === 'approved')
                            <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Approved</span>
                        @else
                            <span class="text-[10px] font-black bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Rejected</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-400">{{ $v->updated_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.verifications.document', ['type' => 'business', 'id' => $v->id]) }}"
                            target="_blank"
                            class="text-xs font-bold text-indigo-600 hover:underline">View Doc</a>
                    </td>
                </tr>
                @endforeach

                @foreach ($evStationsAll as $v)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-bold text-gray-800">{{ $v->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $v->user->email }}</div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-[10px] font-black bg-cyan-50 text-cyan-600 border border-cyan-100 px-2 py-0.5 rounded-full uppercase tracking-wider">EV Station</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-xs font-bold text-gray-700">{{ $v->station_name }}</div>
                        <div class="text-xs text-gray-400">{{ $v->location_address }}</div>
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($v->status === 'approved')
                            <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Approved</span>
                        @else
                            <span class="text-[10px] font-black bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Rejected</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-400">{{ $v->updated_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.verifications.document', ['type' => 'ev_station', 'id' => $v->id]) }}"
                            target="_blank" class="text-xs font-bold text-indigo-600 hover:underline">View License</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
    function toggleRejectForm(id) {
        const el = document.getElementById(id);
        el.classList.toggle('hidden');
        if (!el.classList.contains('hidden')) {
            el.querySelector('input[name="reason"]').focus();
        }
    }
</script>

@endsection