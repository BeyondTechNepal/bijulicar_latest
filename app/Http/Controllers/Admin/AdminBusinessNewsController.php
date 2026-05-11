<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessNews;
use Illuminate\Support\Facades\Storage;

class AdminBusinessNewsController extends Controller
{
    /**
     * List all business-published news articles.
     */
    public function index()
    {
        $articles = BusinessNews::with('business')
            ->latest()
            ->paginate(15);

        return view('admin.business_news.index', compact('articles'));
    }

    /**
     * Delete a business news article.
     */
    public function destroy(BusinessNews $businessNews)
    {
        if ($businessNews->hero_image) {
            Storage::disk('public')->delete($businessNews->hero_image);
        }

        $businessNews->delete();

        return redirect()
            ->route('admin.business-news.index')
            ->with('success', 'Business news article deleted successfully.');
    }
}