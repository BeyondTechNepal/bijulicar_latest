@extends('admin.layout')

@section('title', 'Create News Article')

@section('content')
<div class="max-w-5xl mx-auto p-6 pb-20">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">New Article</h1>
            <p class="text-[10px] text-slate-500 font-mono uppercase mt-1 tracking-widest">Entry Database Node</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">&larr; Back to Archive</a>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- Section 1: Titles & Slug --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 space-y-6">
            <h3 class="font-black text-slate-400 uppercase tracking-widest text-[10px] mb-2">Headline Configuration</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Main Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Slug (URL)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="auto-generated-slug" class="w-full border-slate-200 rounded-xl bg-slate-50 font-mono text-xs text-slate-400">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Title Highlight</label>
                    <input type="text" name="title_highlight" value="{{ old('title_highlight') }}" placeholder="Red Text Part" class="w-full border-slate-200 rounded-xl text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Title Suffix</label>
                    <input type="text" name="title_suffix" value="{{ old('title_suffix') }}" placeholder="Ending Text" class="w-full border-slate-200 rounded-xl text-sm">
                </div>
            </div>
        </div>

        {{-- Section 2: Media & Lead --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Hero Image *</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center">
                        <input type="file" name="hero_image" required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-black file:uppercase file:text-[9px]">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Figure Caption</label>
                    <input type="text" name="figure_caption" value="{{ old('figure_caption') }}" placeholder="Photographer or description" class="w-full border-slate-200 rounded-xl text-sm mt-4">
                </div>
            </div>
            <div class="mt-8">
                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Lead Paragraph (Intro) *</label>
                <textarea name="lead_paragraph" rows="4" required class="w-full border-slate-200 rounded-[1.5rem] text-sm text-slate-600 focus:ring-indigo-500">{{ old('lead_paragraph') }}</textarea>
            </div>
        </div>

        {{-- Section 3: Body Content --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 space-y-8">
            <h3 class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Article Body Sections</h3>
            
            @foreach(['1', '2', '3'] as $num)
            <div class="p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Section {{ $num }} Title</label>
                <input type="text" name="section_{{ $num }}_title" value="{{ old('section_'.$num.'_title') }}" class="w-full border-slate-200 rounded-xl mb-4 font-bold">
                
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Section {{ $num }} Content</label>
                <textarea name="section_{{ $num }}_content" rows="5" class="w-full border-slate-200 rounded-xl text-sm">{{ old('section_'.$num.'_content') }}</textarea>
            </div>
            @endforeach
        </div>

        {{-- Section 4: Tech Specs & Quote --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Tech Specs --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
                <h3 class="font-black text-slate-400 uppercase tracking-widest text-[10px] mb-6">Technical Specifications</h3>
                <div id="tech-specs-container" class="space-y-3 mb-4">
                    <div class="flex gap-2">
                        <input type="text" name="tech_specs[0][key]" placeholder="Label (e.g. Speed)" class="w-1/2 border-slate-200 rounded-xl text-xs font-bold uppercase">
                        <input type="text" name="tech_specs[0][value]" placeholder="Value (e.g. 200km/h)" class="w-1/2 border-slate-200 rounded-xl text-xs">
                    </div>
                </div>
                <button type="button" onclick="addTechSpec()" class="text-[9px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800">+ Add Specification</button>
                
                <div class="mt-6">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Technical Note</label>
                    <input type="text" name="tech_note" value="{{ old('tech_note') }}" placeholder="Small footer note for specs" class="w-full border-slate-200 rounded-xl text-xs">
                </div>
            </div>

            {{-- Quote Block --}}
            <div class="bg-slate-900 rounded-[2rem] shadow-xl p-8 text-white">
                <h3 class="font-black text-slate-500 uppercase tracking-widest text-[10px] mb-6">Article Quote</h3>
                <div class="space-y-4">
                    <textarea name="quote_text" rows="3" placeholder="Enter quote text..." class="w-full bg-slate-800 border-none rounded-xl text-sm text-indigo-200 focus:ring-indigo-500">{{ old('quote_text') }}</textarea>
                    <input type="text" name="quote_author" placeholder="Quote Author" class="w-full bg-slate-800 border-none rounded-xl text-xs font-bold">
                    <input type="text" name="quote_author_title" placeholder="Author Designation" class="w-full bg-slate-800 border-none rounded-xl text-[10px] uppercase tracking-widest">
                </div>
            </div>
        </div>

        {{-- Final: Attribution & Settings --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
                <div class="flex gap-4">
                    <div class="w-20">
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Initials</label>
                        @php
                            $nameParts = explode(' ', Auth::guard('admin')->user()->name);
                            $initials = collect($nameParts)->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                        @endphp
                        <input type="text" name="author_initials" value="{{ old('author_initials', strtoupper($initials)) }}" maxlength="2" required class="w-full border-slate-200 rounded-xl text-center font-mono font-bold text-indigo-600" readonly>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Author Name</label>
                        <input type="text" name="author_name" value="{{ old('author_name', Auth::guard('admin')->user()->name) }}" required class="w-full border-slate-200 rounded-xl font-bold" readonly>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Author Role</label>
                    <input type="text" name="author_role" value="{{ old('author_role', 'Lead Editor') }}" required class="w-full border-slate-200 rounded-xl font-bold">
                </div>
            </div>

            <div class="bg-indigo-50 rounded-[2rem] p-8 flex flex-col justify-center">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="sr-only">
                    <div class="w-12 h-6 bg-slate-300 rounded-full p-1 transition-all group-has-[:checked]:bg-indigo-600">
                        <div class="w-4 h-4 bg-white rounded-full transition-all group-has-[:checked]:translate-x-6"></div>
                    </div>
                    <span class="ml-4 text-[10px] font-black uppercase tracking-widest text-slate-600">Publish Immediately</span>
                </label>
                <p class="mt-4 text-[9px] text-indigo-400 leading-tight font-medium">Drafting locally if unchecked.</p>
            </div>
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white font-black py-6 rounded-[2rem] shadow-2xl hover:bg-indigo-600 transition-all uppercase tracking-widest text-sm">Deploy to News Feed</button>
    </form>
</div>

<script>
    let specIndex = 1;
    function addTechSpec() {
        const container = document.getElementById('tech-specs-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <input type="text" name="tech_specs[${specIndex}][key]" placeholder="Label" class="w-1/2 border-slate-200 rounded-xl text-xs font-bold uppercase">
            <input type="text" name="tech_specs[${specIndex}][value]" placeholder="Value" class="w-1/2 border-slate-200 rounded-xl text-xs">
        `;
        container.appendChild(div);
        specIndex++;
    }
</script>
@endsection