<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NewsArticleController extends Controller
{
    public function index()
    {
        // 1. Explicitly pull the admin from the 'admin' guard
        $admin = Auth::guard('admin')->user();

        // 2. Eager load 'admin' relationship (NOT 'user') to match your News model
        $query = News::with('admin')->orderBy('created_at', 'desc');

        /**
         * 3. Role-Based Filtering
         * We check if the admin HAS the 'super-admin' role (Spatie).
         * If they DON'T have it, we filter the query so they only see their own work.
         */
        if (!$admin->hasRole('superadmin')) {
            $query->where('admin_id', $admin->id);
        }

        $articles = $query->paginate(10);

        return view('admin.news.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateArticle($request);

        // Update to match migration column 'admin_id'
        $validatedData['admin_id'] = Auth::guard('admin')->id();

        if (!$validatedData['admin_id']) {
            return back()->withErrors(['error' => 'Admin session expired. Please log in again.']);
        }

        if ($request->hasFile('hero_image')) {
            $validatedData['hero_image'] = $request->file('hero_image')->store('news/heroes', 'public');
        }

        $validatedData['is_published'] = $request->boolean('is_published');

        News::create($validatedData);

        return redirect()->route('admin.news.index')->with('success', 'Article published successfully.');
    }

    public function edit(News $news)
    {
        // 2. Security Check: Only allow owner or admin
        $this->authorizeAccess($news);

        return view('admin.news.edit', ['article' => $news]);
    }

    public function update(Request $request, News $news)
    {
        // 3. Security Check: Only allow owner or admin
        $this->authorizeAccess($news);

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
        // 4. Security Check: Only allow owner or admin
        $this->authorizeAccess($news);

        if ($news->hero_image) {
            Storage::disk('public')->delete($news->hero_image);
        }

        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Article deleted.');
    }

    /**
     * Internal helper to prevent unauthorized modifications
     */
    protected function authorizeAccess(News $news)
    {
        $admin = Auth::guard('admin')->user();

        // Check if the admin is NOT a super-admin AND doesn't own the article
        if (!$admin->hasRole('super-admin') && $news->admin_id !== $admin->id) {
            abort(403, 'Unauthorized action. You do not own this article.');
        }
    }

    protected function validateArticle(Request $request, $id = null)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'title_suffix' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $id,
            'author_initials' => 'required|string|max:2',
            'author_name' => 'required|string|max:255',
            'author_role' => 'required|string|max:255',
            'hero_image' => ($id ? 'nullable' : 'required') . '|image|max:4096',
            'figure_caption' => 'nullable|string|max:255',
            'lead_paragraph' => 'required|string',
            'section_1_title' => 'nullable|string|max:255',
            'section_1_content' => 'nullable|string',
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
