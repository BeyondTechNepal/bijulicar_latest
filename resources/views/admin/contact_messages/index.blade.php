@extends('admin.layout')
@section('title', 'Admin Management')
@section('page-title', 'System Administrators')

@section('content')

    <div class="p-4 lg:p-6">
        {{-- Slim Header --}}
        <div
            class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 mb-6 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 bg-indigo-500/10 border border-indigo-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Inbound Comms</p>
                    <p class="text-[9px] text-slate-500 font-medium leading-none">Reviewing community inquiries.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 border-l border-slate-800 pl-4">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $messages->total() }}
                    Records</span>
            </div>
        </div>

        {{-- Compact Table Card --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Sender</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Category
                            </th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Message
                            </th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Timestamp</th>
                            <th
                                class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Manage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($messages as $msg)
                            <tr
                                class="group transition-all {{ $msg->status === 'new' ? 'bg-amber-50/30' : 'hover:bg-slate-50/50' }}">
                                {{-- Name & Contact --}}
                                <td class="px-5 py-3">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-bold text-slate-700 leading-tight">{{ $msg->full_name }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $msg->email }}</span>
                                    </div>
                                </td>

                                {{-- Category --}}
                                <td class="px-5 py-3">
                                    <span
                                        class="text-[9px] font-black uppercase tracking-tighter text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                        {{ $msg->category }}
                                    </span>
                                </td>

                                {{-- Message --}}
                                <td class="px-5 py-3">
                                    <p class="text-[11px] text-slate-500 max-w-[180px] truncate">
                                        {{ $msg->message }}
                                    </p>
                                </td>

                                {{-- Date --}}
                                <td class="px-5 py-3 text-right">
                                    <div class="text-[10px] font-bold text-slate-500 uppercase">
                                        {{ $msg->created_at->format('M d') }}</div>
                                    <div class="text-[9px] text-slate-300">{{ $msg->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Status & Actions Combined --}}
                                {{-- Status & Actions Combined --}}
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Mark as Read (only for unread messages) --}}
                                        @if ($msg->status !== 'read')
                                            <form action="{{ route('admin.contact_messages.read', $msg->id) }}"
                                                method="POST">
                                                @csrf
                                                <button title="Mark Read"
                                                    class="p-1.5 text-amber-600 hover:bg-amber-100 rounded-md transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Undo Read (only for read messages) --}}
                                        @if ($msg->status === 'read')
                                            <form action="{{ route('admin.contact_messages.undo', $msg->id) }}"
                                                method="POST">
                                                @csrf
                                                <button title="Undo Read"
                                                    class="p-1.5 text-green-600 hover:bg-green-100 rounded-md transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M19 12H5m7-7v14"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- View --}}
                                        <a href="{{ route('admin.contact_messages.show', $msg->id) }}"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.contact_messages.destroy', $msg->id) }}"
                                            method="POST" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button
                                                class="p-1.5 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-5 py-8 text-center text-[10px] font-black text-slate-300 uppercase tracking-widest">
                                    Buffer Empty</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Slim Pagination --}}
        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </div>

@endsection
