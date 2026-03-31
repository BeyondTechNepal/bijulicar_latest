<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsBanner;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch paginated articles, 5 per page
        // $articles = News::orderBy('published_at', 'asc')->paginate(5);

        $newsItems = News::where('is_published', true)->latest()->get();

        $banner = NewsBanner::where('is_active', true)->latest()->first();

        return view('frontend.pages.news', compact('newsItems', 'banner'));
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
