<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialLink;

class SocialLinkController extends Controller
{
    public function index()
    {
        $links = SocialLink::all();
        return view('admin.social_links.index', compact('links'));
    }

    public function create()
    {
        return view('admin.social_links.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required',
            'url' => 'required|url',
            'icon_class' => 'required'
        ]);

        SocialLink::create($request->all());

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Created successfully');
    }

    public function edit($id)
    {
        $link = SocialLink::findOrFail($id);
        return view('admin.social_links.form', compact('link'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'platform' => 'required',
            'url' => 'required|url',
            'icon_class' => 'required'
        ]);

        $link = SocialLink::findOrFail($id);
        $link->update($request->all());

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        SocialLink::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
