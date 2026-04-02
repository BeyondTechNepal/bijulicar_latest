@extends('dashboard.business.layout')
@section('title', 'Edit Article')
@section('page-title', 'Edit Article')

@section('content')

<div class="max-w-3xl">

    {{-- Back link --}}
    <a href="{{ route('business.news.index') }}"
        class="inline-flex items-center gap-1.5 text-xs font-black text-slate-400 hover:text-slate-700 uppercase tracking-widest mb-6 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to My News
    </a>

    <form method="POST" action="{{ route('business.news.update', $article->slug) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        {{-- ── Hero Image ─────────────────────────────────────────────── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Hero Image</p>

            {{-- Current image --}}
            @if($article->hero_image)
                <div class="mb-4">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-2">Current image</p>
                    <img src="{{ Storage::url($article->hero_image) }}" alt="Current hero"
                        class="w-full max-h-48 object-cover rounded-xl border border-slate-200">
                </div>
            @endif

            <div id="hero-drop-zone"
                class="relative border-2 border-dashed border-slate-200 rounded-xl p-6 text-center cursor-pointer hover:border-purple-400 hover:bg-purple-50/30 transition-all group">
                <input type="file" name="hero_image" id="hero_image" accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    onchange="previewHero(this)">
                <div id="hero-placeholder">
                    <p class="text-sm font-black text-slate-500 uppercase italic">Click to replace image</p>
                    <p class="text-xs text-slate-400 mt-1">Leave blank to keep current</p>
                </div>
                <img id="hero-preview" src="#" alt="Preview" class="hidden mx-auto max-h-40 rounded-xl object-cover">
            </div>
            @error('hero_image')
                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
            @enderror

            <div class="mt-3">
                <input type="text" name="figure_caption"
                    value="{{ old('figure_caption', $article->figure_caption) }}"
                    placeholder="Image caption (optional)"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent placeholder-slate-300">
            </div>
        </div>

        {{-- ── Article Meta ────────────────────────────────────────────── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Article Details</p>

            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">
                    Title <span class="text-red-400">*</span>
                </label>
                <input type="text" name="title" required
                    value="{{ old('title', $article->title) }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent">
                @error('title')
                    <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">Category</label>
                <select name="category_id"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent bg-white">
                    <option value="">— Select a category —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">
                        Author Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="author_name" required
                        value="{{ old('author_name', $article->author_name) }}"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent">
                    @error('author_name')
                        <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">Author Role</label>
                    <input type="text" name="author_role"
                        value="{{ old('author_role', $article->author_role) }}"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent">
                </div>
            </div>
        </div>

        {{-- ── Body Content ────────────────────────────────────────────── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Content</p>

            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">
                    Opening Paragraph <span class="text-red-400">*</span>
                </label>
                <textarea name="lead_paragraph" rows="4" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent resize-y">{{ old('lead_paragraph', $article->lead_paragraph) }}</textarea>
                @error('lead_paragraph')
                    <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-slate-100 pt-5">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">Section 1 Title</label>
                <input type="text" name="section_1_title"
                    value="{{ old('section_1_title', $article->section_1_title) }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent mb-3">
                <textarea name="section_1_content" rows="5"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent resize-y">{{ old('section_1_content', $article->section_1_content) }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-5">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">Pull Quote (optional)</label>
                <textarea name="quote_text" rows="2"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent resize-y mb-2">{{ old('quote_text', $article->quote_text) }}</textarea>
                <input type="text" name="quote_author"
                    value="{{ old('quote_author', $article->quote_author) }}"
                    placeholder="Quote attributed to..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent placeholder-slate-300">
            </div>

            <div class="border-t border-slate-100 pt-5">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">Section 2 Title</label>
                <input type="text" name="section_2_title"
                    value="{{ old('section_2_title', $article->section_2_title) }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent mb-3">
                <textarea name="section_2_content" rows="5"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent resize-y">{{ old('section_2_content', $article->section_2_content) }}</textarea>
            </div>

            {{-- Section 3 --}}
            <div class="border-t border-slate-100 pt-5">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">Section 3 Title</label>
                <input type="text" name="section_2_title" value="{{ old('section_2_title') }}"
                    placeholder="e.g. What's Next For Us"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent placeholder-slate-300 mb-3">
                <textarea name="section_2_content" rows="5"
                    placeholder="Section body..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent placeholder-slate-300 resize-y">{{ old('section_2_content') }}</textarea>
            </div>

            {{-- Section 4 --}}
            <div class="border-t border-slate-100 pt-5">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1.5">Section 4 Title</label>
                <input type="text" name="section_2_title" value="{{ old('section_2_title') }}"
                    placeholder="e.g. What's Next For Us"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent placeholder-slate-300 mb-3">
                <textarea name="section_2_content" rows="5"
                    placeholder="Section body..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent placeholder-slate-300 resize-y">{{ old('section_2_content') }}</textarea>
            </div>
        </div>

        {{-- ── Publish toggle ──────────────────────────────────────────── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Visibility</p>

            <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative">
                    <input type="checkbox" name="is_published" value="1"
                        {{ old('is_published', $article->is_published) ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-purple-500 transition-colors"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                </div>
                <div>
                    <p class="text-sm font-black text-slate-800">Published</p>
                    <p class="text-xs text-slate-400 font-medium">Toggle off to hide from public view.</p>
                </div>
            </label>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('business.news.index') }}"
                class="text-sm font-black text-slate-400 hover:text-slate-700 uppercase tracking-widest transition-colors">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all shadow-lg">
                Update Article
            </button>
        </div>

    </form>
</div>

<script>
function previewHero(input) {
    const preview = document.getElementById('hero-preview');
    const placeholder = document.getElementById('hero-placeholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection