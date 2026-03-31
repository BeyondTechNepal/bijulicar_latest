<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsArticleController extends Controller
{
    public function index()
    {
        $articles = News::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.news.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateArticle($request);

        // Handle Hero Image Upload
        if ($request->hasFile('hero_image')) {
            $validatedData['hero_image'] = $request->file('hero_image')->store('news/heroes', 'public');
        }

        // tech_specs is handled as an array (mapped in validateArticle or processed here)
        // ensure is_published is boolean
        $validatedData['is_published'] = $request->boolean('is_published');

        News::create($validatedData);

        return redirect()->route('admin.news.index')->with('success', 'Article published successfully.');
    }

    public function edit(News $news)
    {
        // Parameter $news is automatically resolved via Slug because of getRouteKeyName() in Model
        return view('admin.news.edit', ['article' => $news]);
    }

    public function update(Request $request, News $news)
    {
        $validatedData = $this->validateArticle($request, $news->id);

        if ($request->hasFile('hero_image')) {
            if ($news->hero_image) {
                Storage::disk('public')->delete($news->hero_image);
            }
            $validatedData['hero_image'] = $request->file('hero_image')->store('news/heroes', 'public');
        }

        $validatedData['is_published'] = $request->boolean('is_published');

        $news->update($validatedData);

        return redirect()->route('admin.news.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(News $news)
    {
        if ($news->hero_image) {
            Storage::disk('public')->delete($news->hero_image);
        }

        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Article deleted.');
    }

    // ================= HELPERS & VALIDATION =================

    protected function validateArticle(Request $request, $id = null)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'title_suffix' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $id,

            // Required per your DB (No Null)
            'author_initials' => 'required|string|max:2',
            'author_name' => 'required|string|max:255',
            'author_role' => 'required|string|max:255',

            // Hero Image is "No Null" in DB, but nullable here to allow keeping old image on update
            'hero_image' => ($id ? 'nullable' : 'required') . '|image|max:4096',
            'figure_caption' => 'nullable|string|max:255',
            'lead_paragraph' => 'required|string',

            'section_1_title' => 'nullable|string|max:255',
            'section_1_content' => 'nullable|string',

            // Fixed: tech_specs and tech_note match your DB column 14 and 15
            'tech_specs' => 'nullable|array',
            'tech_note' => 'nullable|string',

            'section_2_title' => 'nullable|string|max:255',
            'section_2_content' => 'nullable|string',

            'quote_text' => 'nullable|string',
            'quote_author' => 'nullable|string|max:255',
            'quote_author_title' => 'nullable|string|max:255',

            'section_3_title' => 'nullable|string|max:255',
            'section_3_content' => 'nullable|string',
            'is_published' => 'boolean',
        ]);
    }
}
