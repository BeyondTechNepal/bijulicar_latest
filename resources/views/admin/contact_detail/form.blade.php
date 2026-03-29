@extends('admin.layout')
@section('title', isset($contact_detail) ? 'Edit Contact Info' : 'Add Contact Info')
@section('page-title', 'Contact Configuration')

@section('content')
    <div class="p-4 lg:p-6 max-w-4xl">
        {{-- High-Level Header --}}
        <div
            class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-white uppercase tracking-widest">Communication Protocol</p>
                    <p class="text-[11px] text-slate-400 font-medium">Updating public connectivity nodes and operational
                        availability.</p>
                </div>
            </div>
            <a href="{{ route('admin.contact_details.index') }}"
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">
                ← Abort Changes
            </a>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Configuration Manifest</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5">OBJECT_TYPE: CONTACT_RECORD // SYSTEM_STATUS: READY
                </p>
            </div>

            <form method="POST"
                action="{{ isset($contact_detail) ? route('admin.contact_details.update', $contact_detail) : route('admin.contact_details.store') }}"
                class="p-8">

                @csrf
                @if (isset($contact_detail))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Email Input --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Primary Email
                            Node</label>
                        <input type="email" name="email" value="{{ old('email', $contact_detail->email ?? '') }}"
                            placeholder="admin@system.com"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>

                    {{-- Phone Input --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Secure Line
                            (Phone)</label>
                        <input type="text" name="phone_no" value="{{ old('phone_no', $contact_detail->phone_no ?? '') }}"
                            placeholder="+1 (000) 000-0000"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>

                    {{-- Address Input (Full Width) --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Geographic
                            Coordinates (Address)</label>
                        <input type="text" name="address" value="{{ old('address', $contact_detail->address ?? '') }}"
                            placeholder="123 System Plaza, Tech District"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>

                    {{-- Working Hours Input (Full Width) --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Uptime Schedule
                            (Working Hours)</label>
                        <input type="text" name="working_hours"
                            value="{{ old('working_hours', $contact_detail->working_hours ?? '') }}"
                            placeholder="MON - FRI: 09:00 - 18:00"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="mt-10 flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-900/20 active:scale-95">
                        {{ isset($contact_detail) ? 'Commit Updates' : 'Initialize Record' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
