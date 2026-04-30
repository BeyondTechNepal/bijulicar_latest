<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsBannerController extends Controller
{
    public function index()
    {
        $banners = NewsBanner::latest()->get(); // keep as collection
        return view('admin.news_banner.index', compact('banners'));
    }

    public function create()
    {
        // 🚫 Block if already exists
        if (NewsBanner::count() > 0) {
            return redirect()->route('admin.news_banner.index')->with('error', 'Only one banner is allowed.');
        }

        return view('admin.news_banner.form');
    }

    public function store(Request $request)
    {
        // 🚫 Double safety (backend protection)
        if (NewsBanner::count() > 0) {
            return redirect()->route('admin.news_banner.index')->with('error', 'Cannot create more than one banner.');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image',
        ]);

        $data = $request->only(['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news_banners', 'public');
        }

        NewsBanner::create($data);

        return redirect()->route('admin.news_banner.index')->with('success', 'Banner created.');
    }

    public function edit(NewsBanner $news_banner)
    {
        return view('admin.news_banner.form', compact('news_banner'));
    }

    public function update(Request $request, NewsBanner $news_banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image',
        ]);

        $data = $request->only(['title']);

        if ($request->hasFile('image')) {
            if ($news_banner->image) {
                Storage::disk('public')->delete($news_banner->image);
            }

            $data['image'] = $request->file('image')->store('news_banners', 'public');
        }

        $news_banner->update($data);

        return redirect()->route('admin.news_banner.index')->with('success', 'Banner updated.');
    }

    public function destroy(NewsBanner $news_banner)
    {
        if ($news_banner->image) {
            Storage::disk('public')->delete($news_banner->image);
        }

        $news_banner->delete();

        return redirect()->route('admin.news_banner.index')->with('success', 'Banner deleted.');
    }
}
