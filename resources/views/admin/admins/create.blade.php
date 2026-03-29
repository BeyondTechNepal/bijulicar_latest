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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="admin" class="sr-only peer"
                                {{ old('role', 'admin') === 'admin' ? 'checked' : '' }}>
                            <div
                                class="border-2 border-gray-200 rounded-xl p-4 text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-all">
                                <div class="text-sm font-semibold">Admin</div>
                                <div class="text-xs text-gray-500 mt-0.5">Manage users & listings</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="superadmin" class="sr-only peer"
                                {{ old('role') === 'superadmin' ? 'checked' : '' }}>
                            <div
                                class="border-2 border-gray-200 rounded-xl p-4 text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <div class="text-sm font-semibold">Superadmin</div>
                                <div class="text-xs text-gray-500 mt-0.5">Full access</div>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
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
