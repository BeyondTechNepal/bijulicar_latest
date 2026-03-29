@extends('admin.layout')

@section('title', $article ? 'Edit News Article' : 'Add News Article')

@section('content')

@php
    $article = $article ?? null;

    // Vehicle Specs
    $specs = old('vehicle_specs.key')
        ? collect(old('vehicle_specs.key'))->map(fn($key, $i) => [
            'key' => $key,
            'value' => old('vehicle_specs.value')[$i] ?? ''
        ])->toArray()
        : ($article->vehicle_specs ?? []);

    // Related Articles
    $related = old('related_articles.title')
        ? collect(old('related_articles.title'))->map(fn($title, $i) => [
            'title' => $title,
            'category' => old('related_articles.category')[$i] ?? '',
            'image' => old('related_articles.image')[$i] ?? '',
        ])->toArray()
        : ($article->related_articles ?? []);

    // Content Images
    $contentImages = old('content_images') ?? ($article->content_images ?? []);
    $contentImages2 = old('content_images2') ?? ($article->content_images2 ?? []);
@endphp

<div class="p-4 lg:p-8 max-w-6xl mx-auto">
    
    {{-- Slim Header Section --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-black text-white uppercase tracking-[0.2em]">
                    {{ $article ? 'Update Dispatch' : 'New Dispatch' }}
                </p>
                <p class="text-[10px] text-slate-500 font-medium leading-none">
                    {{ $article ? 'Editing existing archive record: #'.$article->id : 'Drafting new editorial content for the platform.' }}
                </p>
            </div>
        </div>
        <a href="{{ route('admin.news.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">
            &larr; Back to Buffer
        </a>
    </div>

    <form action="{{ $article ? route('admin.news.update', $article) : route('admin.news.store') }}"
        method="POST" enctype="multipart/form-data" class="space-y-6">

        @csrf
        @if($article) @method('PUT') @endif

        {{-- ================= CORE METADATA ================= --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-6 py-3 border-b border-slate-100">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Core Metadata</h3>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Main Title</label>
                        <input name="title" value="{{ old('title', $article->title ?? '') }}" 
                            placeholder="Enter article headline..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Subtitle</label>
                        <input name="subtitle" value="{{ old('subtitle', $article->subtitle ?? '') }}"
                            placeholder="Context or lead text..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none">
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-5 mt-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Author Name</label>
                        <input name="author_name" value="{{ old('author_name', $article->author_name ?? '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Author Role</label>
                        <input name="author_role" value="{{ old('author_role', $article->author_role ?? '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Read Time</label>
                        <input name="read_time" value="{{ old('read_time', $article->read_time ?? '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5 mt-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Badge Text</label>
                        <input name="badge_text" value="{{ old('badge_text', $article->badge_text ?? '') }}" placeholder="e.g. EXCLUSIVE" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-indigo-600 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Published Date</label>
                        <input type="date" name="published_at" value="{{ old('published_at', optional($article)->published_at?->format('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= IMAGES SECTION ================= --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-6 py-3 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Visual Assets</h3>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <label class="text-[9px] font-black text-slate-400 uppercase block mb-3">Author Headshot</label>
                        @if($article?->author_image)
                            <img src="{{ asset('storage/'.$article->author_image) }}" class="w-12 h-12 rounded-full object-cover mb-3 border-2 border-white shadow-sm">
                        @endif
                        <input type="file" name="author_image" class="text-[10px] block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-slate-900 cursor-pointer">
                    </div>
                    <div class="p-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <label class="text-[9px] font-black text-slate-400 uppercase block mb-3">Hero Featured Image</label>
                        @if($article?->hero_image)
                            <img src="{{ asset('storage/'.$article->hero_image) }}" class="w-full h-12 object-cover rounded-lg mb-3 shadow-sm">
                        @endif
                        <input type="file" name="hero_image" class="text-[10px] block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-slate-900 cursor-pointer">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Hero Caption</label>
                    <textarea name="hero_caption" placeholder="Image credit or description..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs mt-1 h-16 outline-none">{{ old('hero_caption', $article->hero_caption ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ================= MAIN CONTENT & VERDICT ================= --}}
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Block 1 --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-blue-50/50 px-6 py-3 border-b border-blue-100 flex justify-between">
                        <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Main Content Block</h3>
                        <span class="text-[8px] font-black text-blue-400 uppercase tracking-[0.2em]">Primary HTML</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <textarea name="content_html" placeholder="Body copy..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-mono min-h-[250px] outline-none">{{ old('content_html', $article->content_html ?? '') }}</textarea>
                        
                        <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-xl">
                            <label class="text-[9px] font-black text-indigo-400 uppercase block mb-1">Highlight Pull Quote</label>
                            <textarea name="quote" placeholder="Enter key quote..." class="w-full bg-transparent border-none p-0 text-indigo-900 font-bold italic text-sm placeholder:text-indigo-300 outline-none resize-none h-16">{{ old('quote', $article->quote ?? '') }}</textarea>
                        </div>

                        <div class="pt-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Inline Gallery</label>
                                <button type="button" onclick="addImage1()" class="text-[9px] font-black text-indigo-600 uppercase">+ Add URL</button>
                            </div>
                            <div id="images1" class="space-y-2">
                                @foreach($contentImages as $img)
                                    <div class="flex gap-2 animate-in slide-in-from-top-1">
                                        <input name="content_images[]" value="{{ $img }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none">
                                        <button type="button" onclick="this.parentElement.remove()" class="px-3 text-rose-500 hover:bg-rose-50 rounded-lg">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Block 2 & 3 --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-emerald-50/50 px-6 py-3 border-b border-emerald-100">
                            <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Secondary Section</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <input name="title2" value="{{ old('title2', $article->title2 ?? '') }}" placeholder="Section Title" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold outline-none">
                            <textarea name="content_html2" placeholder="Body copy..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs h-32 outline-none">{{ old('content_html2', $article->content_html2 ?? '') }}</textarea>
                            
                            <div id="images2" class="space-y-2 pt-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-[8px] font-black text-slate-300 uppercase">Gallery 02</label>
                                    <button type="button" onclick="addImage2()" class="text-[14px] text-slate-400 hover:text-indigo-600 leading-none">+</button>
                                </div>
                                @foreach($contentImages2 as $img)
                                    <div class="flex gap-2">
                                        <input name="content_images2[]" value="{{ $img }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-[10px] outline-none">
                                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-purple-50 px-6 py-3 border-b border-purple-100">
                            <h3 class="text-[10px] font-black text-purple-600 uppercase tracking-widest">Closing Verdict</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <input name="title3" value="{{ old('title3', $article->title3 ?? '') }}" placeholder="Verdict Headline" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold outline-none">
                            <textarea name="content_html3" placeholder="Final conclusion..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs h-32 outline-none">{{ old('content_html3', $article->content_html3 ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 overflow-hidden">
                    <div class="bg-slate-800/50 px-6 py-4 border-b border-slate-800">
                        <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Vehicle Data</h3>
                    </div>
                    <div class="p-6">
                        <div id="specs" class="space-y-3">
                            @foreach($specs as $spec)
                                <div class="flex gap-2 animate-in slide-in-from-top-1">
                                    <input name="vehicle_specs[key][]" value="{{ $spec['key'] }}" placeholder="Key" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-white outline-none">
                                    <input name="vehicle_specs[value][]" value="{{ $spec['value'] }}" placeholder="Value" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-indigo-300 font-bold outline-none">
                                    <button type="button" onclick="this.parentElement.remove()" class="text-slate-600 hover:text-rose-500">&times;</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addSpec()" class="w-full mt-4 py-2 border border-slate-800 rounded-xl text-[9px] font-black text-slate-400 uppercase hover:bg-slate-800 hover:text-white transition-all">+ Add Data Row</button>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Related Recs</h3>
                    </div>
                    <div class="p-4">
                        <div id="related" class="space-y-4">
                            @foreach($related as $r)
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2 relative">
                                    <input name="related_articles[title][]" value="{{ $r['title'] }}" placeholder="Article Title" class="w-full bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[10px] font-bold outline-none">
                                    <div class="flex gap-2">
                                        <input name="related_articles[category][]" value="{{ $r['category'] }}" placeholder="Cat" class="w-1/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] uppercase font-black outline-none text-indigo-600">
                                        <input name="related_articles[image][]" value="{{ $r['image'] }}" placeholder="Img URL" class="w-2/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] outline-none">
                                    </div>
                                    <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-5 h-5 bg-white border border-slate-200 rounded-full text-xs text-rose-500 shadow-sm">&times;</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addRelated()" class="w-full mt-4 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">+ Add Link</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-emerald-500 hover:bg-slate-900 text-white py-5 rounded-2xl font-black uppercase tracking-[0.3em] text-xs shadow-xl transition-all transform hover:-translate-y-1">
            {{ $article ? 'Sync Changes to Live Feed' : 'Publish Dispatch to Feed' }}
        </button>

    </form>
</div>

{{-- Dynamic JS with Restyled Templates --}}
<script>
function addSpec() {
    document.getElementById('specs').insertAdjacentHTML('beforeend', `
        <div class="flex gap-2 animate-in slide-in-from-top-1">
            <input name="vehicle_specs[key][]" placeholder="Key" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-white outline-none">
            <input name="vehicle_specs[value][]" placeholder="Value" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-indigo-300 font-bold outline-none">
            <button type="button" onclick="this.parentElement.remove()" class="text-slate-600 hover:text-rose-500">&times;</button>
        </div>
    `);
}

function addImage1() {
    document.getElementById('images1').insertAdjacentHTML('beforeend', `
        <div class="flex gap-2 mb-2 animate-in slide-in-from-top-1">
            <input name="content_images[]" placeholder="Image URL" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none">
            <button type="button" onclick="this.parentElement.remove()" class="px-3 text-rose-500 hover:bg-rose-50 rounded-lg">&times;</button>
        </div>
    `);
}

function addImage2() {
    document.getElementById('images2').insertAdjacentHTML('beforeend', `
        <div class="flex gap-2 mt-2">
            <input name="content_images2[]" placeholder="Image URL" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-[10px] outline-none">
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500">&times;</button>
        </div>
    `);
}

function addRelated() {
    document.getElementById('related').insertAdjacentHTML('beforeend', `
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2 relative animate-in zoom-in-95">
            <input name="related_articles[title][]" placeholder="Article Title" class="w-full bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[10px] font-bold outline-none">
            <div class="flex gap-2">
                <input name="related_articles[category][]" placeholder="Cat" class="w-1/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] uppercase font-black outline-none text-indigo-600">
                <input name="related_articles[image][]" placeholder="Img URL" class="w-2/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] outline-none">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-5 h-5 bg-white border border-slate-200 rounded-full text-xs text-rose-500 shadow-sm">&times;</button>
        </div>
    `);
}
</script>

@endsection