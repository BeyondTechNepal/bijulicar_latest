<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsBanner;
use App\Models\NewsCategory;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch paginated articles, 5 per page
        // $articles = News::orderBy('published_at', 'asc')->paginate(5);

        // $newsItems = News::where('is_published', true)->latest()->get();

        $banner = NewsBanner::where('is_active', true)->latest()->first();

        // 1. Fetch Categories for the Filter Buttons
        // We only need 'id', 'name', and 'slug' for the frontend
        $categories = NewsCategory::orderBy('name', 'asc')->get(['id', 'name', 'slug']);

        // 2. Fetch Initial Articles
        // We 'eager load' the newscategory relationship to prevent N+1 query issues
        $newsItems = News::with('newscategory')->where('is_published', true)->latest()->paginate(12);

        return view('frontend.pages.news', compact('newsItems', 'banner', 'categories'));
    }

    public function show(News $news)
    {
        $banner = NewsBanner::where('is_active', true)->latest()->first();

        // Rename this to $allNews or $relatedNews to avoid confusion in Blade
        $relatedNews = News::where('is_published', true)
            ->where('id', '!=', $news->id) // Optional: don't show the current article in the sidebar
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.pages.news_details', compact('news', 'banner', 'relatedNews'));
    }
}
