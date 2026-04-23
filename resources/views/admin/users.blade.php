@extends('admin.layout')
@section('title', 'User Management')
@section('page-title', 'User Directory')

@section('content')
    @can('manage users', 'admin')
        <div x-data="{ tab: 'all' }" class="space-y-6">

            {{-- Header Stats & Filter --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4 bg-white p-1 rounded-xl border border-gray-200 w-fit shadow-sm">
                    <button @click="tab = 'all'"
                        :class="tab === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        All Users
                    </button>
                    <button @click="tab = 'buyer'"
                        :class="tab === 'buyer' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        Buyers
                    </button>
                    <button @click="tab = 'seller'"
                        :class="tab === 'seller' ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        Sellers
                    </button>
                    <button @click="tab = 'business'"
                        :class="tab === 'business' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        Businesses
                    </button>
                    <button @click="tab = 'ev-station'"
                        :class="tab === 'ev-station' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        Charging Stations
                    </button>
                    <button @click="tab = 'garage'"
                        :class="tab === 'garage' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        Garages
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Security Guard:</span>
                    <span
                        class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-[10px] font-bold uppercase">Admin
                        Session</span>
                </div>
            </div>

            {{-- Main Table Container --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/30">
                    <h3 class="text-sm font-black text-gray-800 tracking-tight">System Records</h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mt-1">Showing users from `web` guard
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">User
                                    Details</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Designation
                                </th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                    Administrative Control</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($users as $user)
                                @php $role = $user->getRoleNames()->first() ?? 'unassigned'; @endphp
                                <tr x-show="tab === 'all' || tab === '{{ $role }}'" x-transition.opacity
                                    class="hover:bg-gray-50/80 transition-all">

                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 flex items-center justify-center text-indigo-700 font-black text-sm shadow-sm">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-900 leading-none">{{ $user->name }}
                                                </div>
                                                <div class="text-[11px] text-gray-400 mt-1 font-medium">{{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-8 py-5">
                                        <div class="flex flex-col gap-1.5">
                                            <span
                                                class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter border
                                                {{ $role === 'buyer' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                                {{ $role === 'seller' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                                {{ $role === 'business' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                                                {{ $role === 'ev-station' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                                                {{ $role === 'garage' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                                {{ $role === 'unassigned' ? 'bg-gray-50 text-gray-400 border-gray-200' : '' }}">
                                                {{ $role }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-medium">Registry:
                                                {{ $user->created_at->format('M Y') }}</span>
                                        </div>
                                    </td>

                                    <td class="px-8 py-5">
                                        <div class="flex items-center justify-end gap-3">
                                            {{-- Role Update --}}
                                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}"
                                                class="flex items-center gap-2 bg-gray-50 p-1 rounded-lg border border-gray-100">
                                                @csrf @method('PATCH')
                                                <select name="role"
                                                    class="bg-transparent text-[10px] font-bold text-gray-600 focus:outline-none px-2 py-0.5 cursor-pointer">
                                                    <option value="buyer" {{ $role === 'buyer' ? 'selected' : '' }}>Buyer
                                                    </option>
                                                    <option value="seller" {{ $role === 'seller' ? 'selected' : '' }}>Seller
                                                    </option>
                                                    <option value="business" {{ $role === 'business' ? 'selected' : '' }}>
                                                        Business</option>
                                                    <option value="ev-station" {{ $role === 'ev-station' ? 'selected' : '' }}>
                                                        Charging Station
                                                    </option>
                                                    <option value="garage" {{ $role === 'garage' ? 'selected' : '' }}>Garage
                                                    </option>
                                                </select>
                                                <button type="submit"
                                                    class="p-1 text-indigo-600 hover:bg-white rounded transition-all shadow-sm border border-transparent hover:border-gray-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>

                                            <div class="w-px h-4 bg-gray-200 mx-1"></div>

                                            {{-- Delete Action --}}
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Archive this user record?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
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
                                    <td colspan="3" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="p-4 bg-gray-50 rounded-full text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No matching
                                                users found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/30">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="max-w-md mx-auto mt-20 text-center space-y-4">
            <div class="inline-flex p-4 bg-red-50 rounded-3xl text-red-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2zm-7-3a3 3 0 11-6 0 3 3 0 016 0z">
                    </path>
                </svg>
            </div>
            <h2 class="text-xl font-black text-gray-900 uppercase">Restricted Access</h2>
            <p class="text-sm text-gray-500 font-medium">Your account lacks the <code
                    class="bg-gray-100 px-2 py-0.5 rounded text-red-600">manage users</code> token required for this module.
            </p>
        </div>
    @endcan
@endsection