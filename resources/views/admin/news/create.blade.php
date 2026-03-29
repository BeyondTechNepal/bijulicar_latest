@extends('admin.layout')
@section('title', 'Add News Article')

@section('content')
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
                <p class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Article Dispatch</p>
                <p class="text-[10px] text-slate-500 font-medium leading-none">Drafting new editorial content for the platform.</p>
            </div>
        </div>
        <a href="{{ route('admin.news.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">
            &larr; Cancel
        </a>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ================= BASIC INFO ================= --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-6 py-3 border-b border-slate-100">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Core Metadata</h3>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Main Title</label>
                        <input type="text" name="title" placeholder="Enter article headline..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Subtitle</label>
                        <input type="text" name="subtitle" placeholder="Context or lead text..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Author Name</label>
                        <input type="text" name="author_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Author Role</label>
                        <input type="text" name="author_role" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Badge Text</label>
                        <input type="text" name="badge_text" placeholder="e.g. EXCLUSIVE" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-indigo-600 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Read Time / Publish Date</label>
                        <div class="flex gap-2">
                            <input type="text" name="read_time" placeholder="5 Min" class="w-1/3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                            <input type="date" name="published_at" class="w-2/3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5 mt-6">
                    <div class="p-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <label class="text-[9px] font-black text-slate-400 uppercase block mb-2 text-center">Author Headshot</label>
                        <input type="file" name="author_image" class="text-[10px] block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-slate-900 cursor-pointer">
                    </div>
                    <div class="p-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                        <label class="text-[9px] font-black text-slate-400 uppercase block mb-2 text-center">Hero Featured Image</label>
                        <input type="file" name="hero_image" class="text-[10px] block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-slate-900 cursor-pointer">
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Hero Caption</label>
                    <textarea name="hero_caption" placeholder="Description for the main image..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none mt-1 h-20"></textarea>
                </div>
            </div>
        </div>

        {{-- ================= CONTENT BLOCKS ================= --}}
        <div class="grid lg:grid-cols-3 gap-6">
            
            {{-- Main Content & Quote --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-slate-50 px-6 py-3 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Main Body Copy</h3>
                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">HTML Supported</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <textarea name="content_html" placeholder="Start writing..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-mono min-h-[300px] outline-none focus:bg-white"></textarea>
                        
                        <div class="bg-indigo-50/50 border border-indigo-100 p-4 rounded-xl">
                            <label class="text-[9px] font-black text-indigo-400 uppercase block mb-1">Highlight Pull Quote</label>
                            <textarea name="quote" placeholder="Enter a key quote to break up the text..." class="w-full bg-transparent border-none p-0 text-indigo-900 font-bold italic text-sm placeholder:text-indigo-300 outline-none resize-none h-16"></textarea>
                        </div>

                        {{-- Dynamic Images 1 --}}
                        <div class="pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-[9px] font-black text-slate-400 uppercase">Gallery Section 01</label>
                                <button type="button" onclick="addImage1()" class="text-[9px] font-black text-indigo-600 uppercase hover:text-indigo-800 tracking-widest">+ Add URL</button>
                            </div>
                            <div id="images1" class="space-y-2">
                                <div class="flex gap-2">
                                    <input name="content_images[]" placeholder="https://unsplash.com/..." class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none">
                                    <button type="button" onclick="this.parentNode.remove()" class="px-3 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2 & 3 --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-blue-50/50 px-6 py-3 border-b border-blue-100">
                            <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Secondary Section</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <input type="text" name="title2" placeholder="Section Title" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold outline-none">
                            <textarea name="content_html2" placeholder="Section text..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs h-32 outline-none"></textarea>
                            
                            <div id="images2" class="space-y-2 mt-2">
                                <label class="text-[8px] font-black text-slate-300 uppercase block">Sub-Gallery</label>
                                <div class="flex gap-2">
                                    <input name="content_images2[]" placeholder="Image URL" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-[10px] outline-none">
                                    <button type="button" onclick="addImage2()" class="bg-slate-100 text-slate-500 px-2 rounded-lg text-lg leading-none">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-emerald-50/50 px-6 py-3 border-b border-emerald-100">
                            <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Final Verdict</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <input type="text" name="title3" placeholder="Verdict Headline" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold outline-none">
                            <textarea name="content_html3" placeholder="Closing thoughts..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs h-32 outline-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Specs & Related --}}
            <div class="space-y-6">
                {{-- Specs --}}
                <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 overflow-hidden">
                    <div class="bg-slate-800/50 px-6 py-4 border-b border-slate-800">
                        <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Vehicle Data</h3>
                    </div>
                    <div class="p-6">
                        <div id="specs" class="space-y-3">
                            <div class="flex gap-2">
                                <input name="vehicle_specs[key][]" placeholder="Key" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-white placeholder:text-slate-600 outline-none">
                                <input name="vehicle_specs[value][]" placeholder="Value" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-indigo-300 font-bold outline-none">
                                <button type="button" onclick="this.parentNode.remove()" class="text-slate-600">&times;</button>
                            </div>
                        </div>
                        <button type="button" onclick="addSpec()" class="w-full mt-4 py-2 border border-slate-800 rounded-xl text-[9px] font-black text-slate-400 uppercase hover:bg-slate-800 hover:text-white transition-all">
                            + Add Data Row
                        </button>
                    </div>
                </div>

                {{-- Related --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Related Content</h3>
                    </div>
                    <div class="p-4">
                        <div id="related" class="space-y-4">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2 relative">
                                <input name="related_articles[title][]" placeholder="Article Title" class="w-full bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[10px] font-bold outline-none">
                                <div class="flex gap-2">
                                    <input name="related_articles[category][]" placeholder="Cat" class="w-1/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] uppercase font-black outline-none text-indigo-600">
                                    <input name="related_articles[image][]" placeholder="Img URL" class="w-2/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] outline-none">
                                </div>
                                <button type="button" onclick="this.parentNode.remove()" class="absolute -top-2 -right-2 w-5 h-5 bg-white border border-slate-200 rounded-full text-xs text-rose-500 shadow-sm">&times;</button>
                            </div>
                        </div>
                        <button type="button" onclick="addRelated()" class="w-full mt-4 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">
                            + Add Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-emerald-500 hover:bg-slate-900 text-white py-5 rounded-2xl font-black uppercase tracking-[0.3em] text-xs shadow-xl shadow-emerald-500/10 transition-all transform hover:-translate-y-1">
            Publish Dispatch to Live Feed
        </button>
    </form>
</div>

{{-- ================= JS ================= --}}
<script>
function addSpec() {
    let div = document.createElement('div');
    div.className = 'flex gap-2 animate-in slide-in-from-top-1 duration-200';
    div.innerHTML = `
        <input name="vehicle_specs[key][]" placeholder="Key" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-white placeholder:text-slate-600 outline-none">
        <input name="vehicle_specs[value][]" placeholder="Value" class="w-1/2 bg-slate-800 border-none rounded-lg px-3 py-2 text-[11px] text-indigo-300 font-bold outline-none">
        <button type="button" onclick="this.parentNode.remove()" class="text-slate-600">&times;</button>
    `;
    document.getElementById('specs').appendChild(div);
}

function addImage1() {
    let div = document.createElement('div');
    div.className = 'flex gap-2 mb-2 animate-in slide-in-from-top-1';
    div.innerHTML = `
        <input name="content_images[]" placeholder="Image URL" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[11px] outline-none">
        <button type="button" onclick="this.parentNode.remove()" class="px-3 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">&times;</button>
    `;
    document.getElementById('images1').appendChild(div);
}

function addImage2() {
    let div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input name="content_images2[]" placeholder="Image URL" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-[10px] outline-none">
        <button type="button" onclick="this.parentNode.remove()" class="px-3 text-rose-500">&times;</button>
    `;
    document.getElementById('images2').appendChild(div);
}

function addRelated() {
    let div = document.createElement('div');
    div.className = 'p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2 relative animate-in zoom-in-95';
    div.innerHTML = `
        <input name="related_articles[title][]" placeholder="Article Title" class="w-full bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[10px] font-bold outline-none">
        <div class="flex gap-2">
            <input name="related_articles[category][]" placeholder="Cat" class="w-1/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] uppercase font-black outline-none text-indigo-600">
            <input name="related_articles[image][]" placeholder="Img URL" class="w-2/3 bg-white border border-slate-100 rounded-lg px-2 py-1.5 text-[9px] outline-none">
        </div>
        <button type="button" onclick="this.parentNode.remove()" class="absolute -top-2 -right-2 w-5 h-5 bg-white border border-slate-200 rounded-full text-xs text-rose-500 shadow-sm">&times;</button>
    `;
    document.getElementById('related').appendChild(div);
}
</script>
@endsection