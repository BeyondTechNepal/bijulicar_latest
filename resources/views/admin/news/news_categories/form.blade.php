@extends('admin.layout')
@section('content')
    <div class="max-w-2xl mx-auto p-6">
        <div class="mb-8">
            <a href="{{ route('admin.news_categories.index') }}"
                class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600">&larr; Return
                to Registry</a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase mt-4">
                {{ isset($category) ? 'Modify Node' : 'Initialize New Node' }}
            </h1>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 p-10">
            <form
                action="{{ isset($category) ? route('admin.news_categories.update', $category->slug) : route('admin.categories.store') }}"
                method="POST" class="space-y-6">
                @csrf
                @if (isset($category))
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-3 tracking-widest">Category
                        Designation Name</label>
                    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
                        placeholder="e.g., Artificial Intelligence" required
                        class="w-full border-slate-200 rounded-2xl p-4 font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    @error('name')
                        <p class="text-red-500 text-[10px] mt-2 font-bold uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl shadow-lg hover:bg-indigo-600 transition-all uppercase tracking-widest text-xs">
                        {{ isset($category) ? 'Update Registry' : 'Deploy to Database' }}
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-[9px] text-slate-400 mt-8 font-mono uppercase tracking-widest">
            Node encryption: Standard | Protocol: Admin-v2
        </p>
    </div>
@endsection
