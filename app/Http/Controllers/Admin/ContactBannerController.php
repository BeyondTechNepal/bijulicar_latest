<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactBannerController extends Controller
{
    public function index()
    {
        $banners = ContactBanner::latest()->get();
        return view('admin.contact_banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.contact_banner.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image',
        ]);

        $path = $request->file('image')->store('contact_banners', 'public');

        ContactBanner::create([
            'title' => $request->title,
            'image' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.contact_banner.index');
    }

    public function edit(ContactBanner $contact_banner)
    {
        return view('admin.contact_banner.form', compact('contact_banner'));
    }

    public function update(Request $request, ContactBanner $contact_banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($contact_banner->image);
            $contact_banner->image = $request->file('image')->store('contact_banners', 'public');
        }

        $contact_banner->update([
            'title' => $request->title,
            'image' => $contact_banner->image,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.contact_banner.index');
    }

    public function destroy(ContactBanner $contact_banner)
    {
        Storage::disk('public')->delete($contact_banner->image);
        $contact_banner->delete();

        return back();
    }
}
