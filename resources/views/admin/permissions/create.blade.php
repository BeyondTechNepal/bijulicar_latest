@extends('admin.layout')
@section('title', 'New Permission') @section('page-title', 'Create Permission') @section('content') <div
    class="max-w-lg">
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">New Permission</h3>
            <p class="text-xs font-mono text-gray-400 mt-0.5">Will be created with guard_name = web</p>
        </div>
        <form method="POST" action="{{ route('admin.permissions.store') }}" class="px-6 py-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Permission Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="e.g. compare vehicles"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700">Create
                    Permission</button>
                <a href="{{ route('admin.permissions.index') }}"
                    class="flex-1 text-center border border-gray-300 text-gray-700 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
