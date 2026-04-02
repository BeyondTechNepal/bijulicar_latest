<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\BusinessNews;
use App\Models\NewsBanner;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $banner     = NewsBanner::where('is_active', true)->latest()->first();
        $categories = NewsCategory::orderBy('name', 'asc')->get(['id', 'name', 'slug']);

        // ── Admin news ───────────────────────────────────────────────────
        $adminQuery = News::with('newscategory')
            ->where('is_published', true);

        // ── Business news ────────────────────────────────────────────────
        $businessQuery = BusinessNews::with(['newscategory', 'business.businessVerification'])
            ->where('is_published', true);

        // ── Category filter (applied to both sources) ────────────────────
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $adminQuery->whereHas('newscategory', fn($q) => $q->where('slug', $categorySlug));
            $businessQuery->whereHas('newscategory', fn($q) => $q->where('slug', $categorySlug));
        }

        // ── Fetch & normalise ────────────────────────────────────────────
        $adminNews = $adminQuery->latest()->get()->map(function ($item) {
            return [
                'id'          => 'admin_' . $item->id,
                'title'       => $item->title . ($item->title_highlight ? ' ' . $item->title_highlight : ''),
                'slug'        => $item->slug,
                'route'       => 'news.show',
                'hero_image'  => $item->hero_image,
                'lead_paragraph' => $item->lead_paragraph,
                'author_name' => $item->author_name,
                'category'    => $item->newscategory?->name,
                'created_at'  => $item->created_at,
                'type'        => 'admin',
                'business_name' => null,
                'business_id' => null,
                '_original'   => $item, // keep for pagination compat
            ];
        });

        $businessNews = $businessQuery->latest()->get()->map(function ($item) {
            return [
                'id'          => 'biz_' . $item->id,
                'title'       => $item->title,
                'slug'        => $item->slug,
                'route'       => 'business.news.show',
                'hero_image'  => $item->hero_image,
                'lead_paragraph' => $item->lead_paragraph,
                'author_name' => $item->author_name,
                'category'    => $item->newscategory?->name,
                'created_at'  => $item->created_at,
                'type'        => 'business',
                'business_name' => $item->business_name,
                'business_id' => $item->user_id,
                '_original'   => $item,
            ];
        });

        // ── Merge, sort newest-first, paginate manually ──────────────────
        $merged = $adminNews->concat($businessNews)->sortByDesc('created_at')->values();

        // Manual paginate (15 per page)
        $perPage     = 12;
        $currentPage = $request->get('page', 1);
        $total       = $merged->count();
        $newsItems   = $merged->forPage($currentPage, $perPage);

        // Wrap in a LengthAwarePaginator so the Blade view gets pagination links
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $newsItems,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('frontend.pages.news', [
            'newsItems'  => $paginator,
            'banner'     => $banner,
            'categories' => $categories,
        ]);
    }

    public function show(News $news)
    {
    $news->load('newscategory');

    $banner = NewsBanner::where('is_active', true)->latest()->first();

    $relatedAdminNews = News::where('is_published', true)
        ->where('id', '!=', $news->id)
        ->latest()
        ->take(3)
        ->get()
        ->map(fn($n) => [
            'title'      => trim($n->title . ' ' . $n->title_highlight),
            'slug'       => $n->slug,
            'route'      => 'news.show',
            'created_at' => $n->created_at,
            'hero_image' => $n->hero_image,
            'type'       => 'admin',
        ]);

    $relatedBusinessNews = BusinessNews::where('is_published', true)
        ->latest()
        ->take(3)
        ->get()
        ->map(fn($n) => [
            'title'      => $n->title,
            'slug'       => $n->slug,
            'route'      => 'business.news.show',
            'created_at' => $n->created_at,
            'hero_image' => $n->hero_image,
            'type'       => 'business',
        ]);

    $recentArticles = $relatedAdminNews->concat($relatedBusinessNews)
        ->sortByDesc('created_at')
        ->take(5)
        ->values();

    return view('frontend.pages.news_details', compact('news', 'banner', 'recentArticles'));
    }
}