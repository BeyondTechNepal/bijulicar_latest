<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsFilterController extends Controller
{
    public function filter(Request $request)
    {
        $slug = $request->get('category');

        $query = News::with('newscategory')->where('is_published', true);

        // Filter by category slug if it's not 'all'
        if ($slug && $slug !== 'all') {
            $query->whereHas('newscategory', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $articles = $query->latest()->get();

        // We return a specific "partial" view that only contains the cards
        return view('frontend.news._list_partial', compact('articles'))->render();
    }
}
