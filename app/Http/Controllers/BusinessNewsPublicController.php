<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\BusinessNews;
use App\Models\NewsBanner;
use App\Models\News;

class BusinessNewsPublicController extends Controller
{
    /**
     * Show a single published business news article.
     * URL: /business-news/{slug}
     */
    public function show(BusinessNews $news)
    {
        // Only show published articles publicly
        if (! $news->is_published) {
            abort(404);
        }

        // Load the business and their verification for the "by" line
        $news->load(['business.businessVerification', 'newscategory']);

        $banner = NewsBanner::where('is_active', true)->latest()->first();

        // Sidebar: recent admin news + recent business news combined
        $relatedNews = News::where('is_published', true)
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($n) => [
                'title'      => $n->title . ' ' . $n->title_highlight,
                'slug'       => $n->slug,
                'route'      => 'news.show',
                'created_at' => $n->created_at,
                'hero_image' => $n->hero_image,
                'type'       => 'admin',
            ]);

        $relatedBusinessNews = BusinessNews::where('is_published', true)
            ->where('id', '!=', $news->id)
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

        $recentArticles = $relatedNews->concat($relatedBusinessNews)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        $newsDetailAds = Advertisement::liveForPlacement('news_detail_sidebar')->get();

        return view('frontend.pages.business_news_detail', compact('news', 'banner', 'recentArticles', 'newsDetailAds'));
    }
}