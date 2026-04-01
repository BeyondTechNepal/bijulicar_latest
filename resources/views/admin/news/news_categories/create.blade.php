@extends('admin.layout')

@section('title', 'Deploy Category')

@section('content')
    <div class="max-w-2xl mx-auto p-6">
        <div class="mb-10">
            <a href="{{ route('admin.news_categories.index') }}"
                class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">&larr;
                Back to Registry</a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase mt-4">New Category Node</h1>
            <p class="text-[10px] text-slate-500 font-mono uppercase mt-1 tracking-widest">Entry Database Node</p>
        </div>

        <form action="{{ route('admin.news_categories.store') }}" method="POST"
            class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-10 space-y-8">
            @csrf

            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase mb-3 tracking-widest">Category Name
                    *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Artificial Intelligence"
                    required
                    class="w-full border-slate-200 rounded-2xl p-4 font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                @error('name')
                    <p class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-tight">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl shadow-xl hover:bg-indigo-600 transition-all uppercase tracking-widest text-xs">
                Deploy to Database
            </button>
        </form>
    </div>
@endsection
