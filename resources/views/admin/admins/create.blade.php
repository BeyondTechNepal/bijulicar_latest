@extends('admin.layout')
@section('title', 'New Admin')
@section('page-title', 'Create Admin Account')
@section('content')
    <div class="max-w-lg">
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">New Admin Account</h3>
                <p class="text-xs font-mono text-gray-400 mt-0.5">Creates record in admins table with guard_name = admin</p>
            </div>
            <form method="POST" action="{{ route('admin.admins.store') }}" class="px-6 py-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 -mt-2">{{ $message }}</p>
                @enderror
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($roles as $role)
                        @php
                            // Assign colors based on the role name
                            $style = match(strtolower($role->name)) {
                                'superadmin' => ['border' => 'peer-checked:border-red-500', 'bg' => 'peer-checked:bg-red-50', 'text' => 'text-red-600'],
                                'admin'      => ['border' => 'peer-checked:border-yellow-500', 'bg' => 'peer-checked:bg-yellow-50', 'text' => 'text-yellow-600'],
                                'newsadmin'  => ['border' => 'peer-checked:border-blue-500', 'bg' => 'peer-checked:bg-blue-50', 'text' => 'text-blue-600'],
                                default      => ['border' => 'peer-checked:border-slate-500', 'bg' => 'peer-checked:bg-slate-50', 'text' => 'text-slate-600'],
                            };
                        @endphp

                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="{{ $role->name }}" class="sr-only peer"
                                {{ old('role') === $role->name ? 'checked' : '' }}>
                            
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all duration-300
                                        peer-checked:shadow-md {{ $style['border'] }} {{ $style['bg'] }}">
                                
                                {{-- Role Name (Formatted) --}}
                                <div class="text-sm font-black uppercase italic {{ $style['text'] }}">
                                    {{ ucwords(str_replace(['admin', '_'], [' Admin', ' '], $role->name)) }}
                                </div>

                                {{-- Dynamic Description --}}
                                <div class="text-[10px] text-gray-500 mt-1 leading-tight">
                                    @if($role->name === 'superadmin')
                                        Full system access & root privileges
                                    @elseif($role->name === 'newsadmin')
                                        Manage news, categories & media
                                    @else
                                        General administrative access
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 bg-gray-900 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700">Create
                        Admin</button>
                    <a href="{{ route('admin.admins.index') }}"
                        class="flex-1 text-center border border-gray-300 text-gray-700 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
