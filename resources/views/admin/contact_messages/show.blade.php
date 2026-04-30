@extends('admin.layout')
@section('title', 'Message Intelligence')
@section('page-title', 'Communication Decryption')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    
    {{-- Header Section --}}
    <div class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-black text-white uppercase tracking-widest">Message Intelligence</p>
                <p class="text-[11px] text-slate-400 font-medium">Detailed log of inbound communication UID: {{ $message->id }}</p>
            </div>
        </div>
        <a href="{{ route('admin.contact_messages.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">
            &larr; Go Back
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-4 py-3 rounded-xl mb-6 uppercase tracking-tight">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Data Card --}}
    <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Sender Dossier</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5">SOURCE: WEB_FORM // STATUS: {{ strtoupper($message->status) }}</p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg border {{ $message->status === 'new' ? 'bg-amber-50 border-amber-100 text-amber-700' : 'bg-emerald-50 border-emerald-100 text-emerald-700' }}">
                <span class="w-1 h-1 rounded-full {{ $message->status === 'new' ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                <span class="text-[10px] font-black uppercase tracking-widest">{{ $message->status }}</span>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Personal Intel --}}
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Full Name</label>
                    <p class="text-sm font-bold text-slate-700 tracking-tight">{{ $message->full_name }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Contact Details</label>
                    <p class="text-sm font-bold text-slate-700 tracking-tight">{{ $message->email }}</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $message->phone ?? 'NO_PHONE_PROVIDED' }}</p>
                </div>
            </div>

            {{-- Metadata --}}
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Category</label>
                    <span class="inline-flex text-[10px] font-black bg-slate-100 text-slate-600 px-2 py-1 rounded uppercase tracking-tighter">
                        {{ $message->category }}
                    </span>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Timestamp</label>
                    <p class="text-sm font-bold text-slate-700 tracking-tight">{{ $message->created_at->format('M d, Y - H:i') }}</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $message->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Message Content --}}
            <div class="col-span-1 md:col-span-2 mt-4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Transmission Content</label>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-slate-600 text-sm leading-relaxed italic">
                    "{{ $message->message }}"
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
            <div class="flex gap-3">
                @if ($message->status !== 'read')
                    <form action="{{ route('admin.contact_messages.read', $message->id) }}" method="POST">
                        @csrf
                        <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-900/20">
                            Mark as Read
                        </button>
                    </form>
                @endif
                
                <form action="{{ route('admin.contact_messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Permanently purge this record?')">
                    @csrf @method('DELETE')
                    <button class="bg-white border border-slate-200 text-rose-600 px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-50 transition-all">
                        Delete
                    </button>
                </form>
            </div>

            <a href="{{ route('admin.contact_messages.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                Cancel
            </a>
        </div>
    </div>
</div>
@endsection