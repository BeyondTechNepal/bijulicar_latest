@extends('admin.layout')
@section('title', 'Contact Management')
@section('page-title', 'Contact Details')

@section('content')
    <div class="p-4 lg:p-6">
        {{-- Action Header --}}
        <div
            class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-white uppercase tracking-widest">Communication Nodes</p>
                    <p class="text-[11px] text-slate-400 font-medium">Manage public-facing contact information and
                        operational hours.</p>
                </div>
            </div>
            {{-- <a href="{{ route('admin.contact_details.create') }}"
                class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-900/20 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Contact
            </a> --}}
        </div>

        {{-- Main Inventory Card --}}
        <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Registered Locations</h3>
                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">DB_TABLE: CONTACT_DETAILS // STATUS: ACTIVE</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $contacts->count() }}
                        Entry Points</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Connect
                                Identity</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Location
                                Data</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Operational Hours</th>
                            <th
                                class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                System Management</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($contacts as $contact)
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                {{-- Email & Phone --}}
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-700 tracking-tight">{{ $contact->email }}</span>
                                        <span
                                            class="text-[11px] font-mono text-indigo-500 font-medium">{{ $contact->phone_no }}</span>
                                    </div>
                                </td>

                                {{-- Address --}}
                                <td class="px-8 py-5">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-300 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="text-[11px] text-slate-500 leading-relaxed max-w-[200px]">
                                            {{ $contact->address }}</p>
                                    </div>
                                </td>

                                {{-- Hours --}}
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-tighter">
                                        {{ $contact->working_hours }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.contact_details.edit', $contact) }}"
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                            title="Modify Record">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        {{-- <form method="POST" action="{{ route('admin.contact_details.destroy', $contact) }}"
                                            onsubmit="return confirm('Execute permanent deletion of this record?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
