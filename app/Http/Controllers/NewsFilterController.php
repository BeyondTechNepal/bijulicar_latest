<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\BusinessNews;
use Illuminate\Http\Request;

class NewsFilterController extends Controller
{
    public function filter(Request $request)
    {
        $slug = $request->get('category');

        // ── Admin news ───────────────────────────────────────────────────
        $adminQuery = News::with('newscategory')->where('is_published', true);

        // ── Business news ────────────────────────────────────────────────
        $businessQuery = BusinessNews::with(['newscategory', 'business.businessVerification'])
            ->where('is_published', true);

        // ── Category filter ──────────────────────────────────────────────
        if ($slug && $slug !== 'all') {
            $adminQuery->whereHas('newscategory', fn($q) => $q->where('slug', $slug));
            $businessQuery->whereHas('newscategory', fn($q) => $q->where('slug', $slug));
        }

        // ── Normalise admin news to arrays ───────────────────────────────
        $adminNews = $adminQuery->latest()->get()->map(fn($item) => [
            'id'             => 'admin_' . $item->id,
            'title'          => $item->title . ($item->title_highlight ? ' ' . $item->title_highlight : ''),
            'slug'           => $item->slug,
            'route'          => 'news.show',
            'hero_image'     => $item->hero_image,
            'lead_paragraph' => $item->lead_paragraph,
            'author_name'    => $item->author_name,
            'category'       => $item->newscategory?->name,
            'created_at'     => $item->created_at,
            'type'           => 'admin',
            'business_name'  => null,
            'business_id'    => null,
        ]);

        // ── Normalise business news to arrays ────────────────────────────
        $businessNews = $businessQuery->latest()->get()->map(fn($item) => [
            'id'             => 'biz_' . $item->id,
            'title'          => $item->title,
            'slug'           => $item->slug,
            'route'          => 'business.news.show',
            'hero_image'     => $item->hero_image,
            'lead_paragraph' => $item->lead_paragraph,
            'author_name'    => $item->author_name,
            'category'       => $item->newscategory?->name,
            'created_at'     => $item->created_at,
            'type'           => 'business',
            'business_name'  => $item->business_name,
            'business_id'    => $item->user_id,
        ]);

        // ── Merge and sort newest-first ──────────────────────────────────
        $articles = $adminNews->concat($businessNews)->sortByDesc('created_at')->values();

        return view('frontend.news._list_partial', compact('articles'))->render();
    }
}