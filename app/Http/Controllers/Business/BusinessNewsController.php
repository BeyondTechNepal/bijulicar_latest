<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessNews;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessNewsController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────

    public function index()
    {
        $articles = BusinessNews::where('user_id', Auth::id())
            ->with('newscategory')
            ->latest()
            ->paginate(10);

        return view('dashboard.business.news.index', compact('articles'));
    }

    // ── Create ────────────────────────────────────────────────────────────

    public function create()
    {
        $categories = NewsCategory::orderBy('name')->get();

        return view('dashboard.business.news.create', compact('categories'));
    }

    // ── Store ─────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $this->validateArticle($request);

        $data['user_id'] = Auth::id();
        $data['author_name'] = $data['author_name'] ?? Auth::user()->name;
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $request->file('hero_image')
                ->store('business-news/heroes', 'public');
        }

        BusinessNews::create($data);

        return redirect()
            ->route('business.news.index')
            ->with('success', 'Article published successfully.');
    }

    // ── Edit ──────────────────────────────────────────────────────────────

    public function edit(BusinessNews $news)
    {
        $this->authorizeOwner($news);

        $categories = NewsCategory::orderBy('name')->get();

        return view('dashboard.business.news.edit', [
            'article'    => $news,
            'categories' => $categories,
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────────

    public function update(Request $request, BusinessNews $news)
    {
        $this->authorizeOwner($news);

        $data = $this->validateArticle($request, $news->id);

        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('hero_image')) {
            if ($news->hero_image) {
                Storage::disk('public')->delete($news->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')
                ->store('business-news/heroes', 'public');
        }

        $news->update($data);

        return redirect()
            ->route('business.news.index')
            ->with('success', 'Article updated successfully.');
    }

    // ── Destroy ───────────────────────────────────────────────────────────

    public function destroy(BusinessNews $news)
    {
        $this->authorizeOwner($news);

        if ($news->hero_image) {
            Storage::disk('public')->delete($news->hero_image);
        }

        $news->delete();

        return redirect()
            ->route('business.news.index')
            ->with('success', 'Article deleted.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Abort with 403 if the authenticated user does not own this article.
     */
    protected function authorizeOwner(BusinessNews $news): void
    {
        if ($news->user_id != Auth::id()) {
            abort(403, 'You do not own this article.');
        }
    }

    /**
     * Shared validation rules for store & update.
     */
    protected function validateArticle(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:business_news,slug,' . $ignoreId,
            'category_id'       => 'nullable|exists:news_categories,id',
            'hero_image'        => ($ignoreId ? 'nullable' : 'required') . '|image|max:4096',
            'figure_caption'    => 'nullable|string|max:255',
            'lead_paragraph'    => 'required|string',
            'section_1_title'   => 'nullable|string|max:255',
            'section_1_content' => 'nullable|string',
            'quote_text'        => 'nullable|string',
            'quote_author'      => 'nullable|string|max:255',
            'section_2_title'   => 'nullable|string|max:255',
            'section_2_content' => 'nullable|string',
            'section_3_title'   => 'nullable|string|max:255',
            'section_3_content' => 'nullable|string',
            'section_4_title'   => 'nullable|string|max:255',
            'section_4_content' => 'nullable|string',
            'author_name'       => 'required|string|max:255',
            'author_role'       => 'nullable|string|max:255',
            'is_published'      => 'boolean',
        ]);
    }
}   