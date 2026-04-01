@extends('admin.layout')

@section('title', 'Category Registry')

@section('content')
    <div class="max-w-5xl mx-auto p-6 pb-20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Category Registry</h1>
                <p class="text-[10px] text-slate-500 font-mono uppercase mt-1 tracking-widest">Structural Taxonomy Nodes</p>
            </div>
            <a href="{{ route('admin.news_categories.create') }}"
                class="bg-slate-900 text-white px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg">
                + Initialize New Node
            </a>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Designation</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Slug (URL)</th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Relational Count
                        </th>
                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($categories as $category)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6">
                                <span class="text-sm font-bold text-slate-800">{{ $category->name }}</span>
                            </td>
                            <td class="p-6">
                                <span
                                    class="font-mono text-[10px] text-slate-400 bg-slate-100 px-2 py-1 rounded-lg border border-slate-200">
                                    {{ $category->slug }}
                                </span>
                            </td>
                            <td class="p-6">
                                <span
                                    class="text-xs font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                                    {{ $category->news_count }} Articles
                                </span>
                            </td>
                            <td class="p-6 text-right space-x-3">
                                <a href="{{ route('admin.news_categories.edit', $category->slug) }}"
                                    class="text-[10px] font-black uppercase text-slate-400 hover:text-indigo-600 transition-colors">Modify</a>

                                <form action="{{ route('admin.news_categories.destroy', $category->slug) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[10px] font-black uppercase text-red-300 hover:text-red-600 transition-colors"
                                        onclick="return confirm('Confirm Node Deletion?')">Drop</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
