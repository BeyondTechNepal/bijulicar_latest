<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::latest()->get();
        return view('admin.home_banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.home_banner.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image1' => 'nullable|image',
            'image2' => 'nullable|image',
            'image3' => 'nullable|image',
        ]);

        $data = $request->only(['title', 'subtitle']);

        foreach (['image1', 'image2', 'image3'] as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('home_banners', 'public');
            }
        }

        HomeBanner::create($data);

        return redirect()->route('admin.home_banner.index');
    }

    public function edit(HomeBanner $home_banner)
    {
        return view('admin.home_banner.form', compact('home_banner'));
    }

    public function update(Request $request, HomeBanner $home_banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image1' => 'nullable|image',
            'image2' => 'nullable|image',
            'image3' => 'nullable|image',
        ]);

        $data = $request->only(['title', 'subtitle']);

        foreach (['image1', 'image2', 'image3'] as $img) {
            if ($request->hasFile($img)) {
                if ($home_banner->$img) {
                    Storage::disk('public')->delete($home_banner->$img);
                }
                $data[$img] = $request->file($img)->store('home_banners', 'public');
            }
        }

        $home_banner->update($data);

        return redirect()->route('admin.home_banner.index');
    }

    public function destroy(HomeBanner $home_banner)
    {
        foreach (['image1', 'image2', 'image3'] as $img) {
            if ($home_banner->$img) {
                Storage::disk('public')->delete($home_banner->$img);
            }
        }

        $home_banner->delete();
        return back();
    }
}
