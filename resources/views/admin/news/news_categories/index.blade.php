@extends('admin.layout')
@section('content')
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Category Registry</h1>
                <p class="text-[10px] text-slate-500 font-mono uppercase mt-1 tracking-widest">Structural Taxonomy Nodes</p>
            </div>
            <a href="{{ route('admin.news_categories.create') }}"
                class="bg-slate-900 text-white px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">
                + Add New Category
            </a>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Category Name</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Slug</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Article Count</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($categories as $category)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6">
                                <span class="text-sm font-bold text-slate-700">{{ $category->name }}</span>
                            </td>
                            <td class="p-6">
                                <span
                                    class="font-mono text-[10px] text-slate-400 bg-slate-100 px-2 py-1 rounded-md">{{ $category->slug }}</span>
                            </td>
                            <td class="p-6">
                                <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                    {{ $category->news_count }} Entries
                                </span>
                            </td>
                            <td class="p-6 text-right space-x-4">
                                <a href="{{ route('admin.news_categories.edit', $category->slug) }}"
                                    class="text-[10px] font-black uppercase text-slate-400 hover:text-indigo-600">Edit</a>
                                <form action="{{ route('admin.news_categories.destroy', $category->slug) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-[10px] font-black uppercase text-red-400 hover:text-red-600"
                                        onclick="return confirm('Archive this node?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
