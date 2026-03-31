@extends('admin.layout')

@section('title', 'Create News Article')

@section('content')
    <div class="max-w-5xl mx-auto p-6">

        {{-- Breadcrumbs / Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create News Entry</h1>
                <p class="text-sm text-gray-500">Drafting a new article for the news archive.</p>
            </div>
            <a href="{{ route('admin.news.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                &larr; Back to Archive
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm">
                <p class="font-bold">Please fix the following errors:</p>
                <ul class="list-disc ml-5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- 1. Core Content Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-bold text-gray-700 uppercase tracking-wider text-xs">Primary Content</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Main Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Slug (Auto-generated)</label>
                            <input type="text" name="slug" value="{{ old('slug') }}"
                                placeholder="leave-blank-for-auto"
                                class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Title Highlight (Prefix)</label>
                            <input type="text" name="title_highlight" value="{{ old('title_highlight') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Title Suffix</label>
                            <input type="text" name="title_suffix" value="{{ old('title_suffix') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Lead Paragraph (Main Body) *</label>
                        <textarea name="lead_paragraph" rows="6" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('lead_paragraph') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. Author & Media Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-700 uppercase tracking-wider text-xs mb-4">Author Details</h3>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-20">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Initials *</label>
                                <input type="text" name="author_initials" maxlength="2"
                                    value="{{ old('author_initials') }}" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm uppercase text-center font-mono">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Author Full Name *</label>
                                <input type="text" name="author_name" value="{{ old('author_name') }}" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Author Role *</label>
                            <input type="text" name="author_role" value="{{ old('author_role') }}" required
                                class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="e.g. Lead Developer">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-700 uppercase tracking-wider text-xs mb-4">Visual Media</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Hero Image *</label>
                            <input type="file" name="hero_image" required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Figure Caption</label>
                            <input type="text" name="figure_caption" value="{{ old('figure_caption') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="Image description...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Dynamic Technical Specs (tech_specs JSON) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-700 uppercase tracking-wider text-xs">Technical Specifications</h3>
                    <button type="button" onclick="addSpecRow()"
                        class="text-xs bg-indigo-600 text-white px-3 py-1 rounded-md hover:bg-indigo-700 transition">
                        + Add Spec
                    </button>
                </div>
                <div id="tech-specs-container" class="space-y-3">
                    <div class="flex gap-3 spec-row">
                        <input type="text" name="tech_specs[0][key]" placeholder="Label (e.g. Engine)"
                            class="flex-1 border-gray-300 rounded-lg shadow-sm text-sm">
                        <input type="text" name="tech_specs[0][value]" placeholder="Value (e.g. V8 Turbo)"
                            class="flex-1 border-gray-300 rounded-lg shadow-sm text-sm">
                        <button type="button" onclick="this.parentElement.remove()"
                            class="text-red-400 hover:text-red-600">&times;</button>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Technical Note (tech_notetext)</label>
                    <textarea name="tech_notetext" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">{{ old('tech_notetext') }}</textarea>
                </div>
            </div>

            {{-- 4. Sections & Quotes (Optional) --}}
            <div class="bg-gray-100 p-1 rounded-xl grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <h3 class="font-bold text-gray-700 uppercase tracking-wider text-xs mb-3">Section 1</h3>
                    <input type="text" name="section_1_title" placeholder="Title"
                        value="{{ old('section_1_title') }}"
                        class="w-full mb-3 border-gray-300 rounded-lg shadow-sm text-sm">
                    <textarea name="section_1_content" rows="3" placeholder="Content..."
                        class="w-full border-gray-300 rounded-lg shadow-sm text-sm">{{ old('section_1_content') }}</textarea>
                </div>
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <h3 class="font-bold text-gray-700 uppercase tracking-wider text-xs mb-3">Quote Section</h3>
                    <textarea name="quote_text" rows="2" placeholder="The quote..."
                        class="w-full mb-3 border-gray-300 rounded-lg shadow-sm text-sm italic">{{ old('quote_text') }}</textarea>
                    <input type="text" name="quote_author" placeholder="Author Name"
                        class="w-full mb-2 border-gray-300 rounded-lg shadow-sm text-xs">
                    <input type="text" name="quote_author_title" placeholder="Author Title"
                        class="w-full border-gray-300 rounded-lg shadow-sm text-xs">
                </div>
            </div>

            {{-- Submission --}}
            <div class="flex items-center justify-between p-6 bg-white rounded-xl border border-gray-200 shadow-md">
                <div class="flex items-center">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                        {{ old('is_published') ? 'checked' : '' }}
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="is_published" class="ml-2 block text-sm text-gray-900 font-medium">
                        Publish Immediately
                    </label>
                </div>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">
                    Save & Publish Article
                </button>
            </div>
        </form>
    </div>

    <script>
        let specCount = 1;

        function addSpecRow() {
            const container = document.getElementById('tech-specs-container');
            const row = `
            <div class="flex gap-3 spec-row">
                <input type="text" name="tech_specs[${specCount}][key]" placeholder="Label" class="flex-1 border-gray-300 rounded-lg shadow-sm text-sm">
                <input type="text" name="tech_specs[${specCount}][value]" placeholder="Value" class="flex-1 border-gray-300 rounded-lg shadow-sm text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">&times;</button>
            </div>`;
            container.insertAdjacentHTML('beforeend', row);
            specCount++;
        }
    </script>
@endsection
