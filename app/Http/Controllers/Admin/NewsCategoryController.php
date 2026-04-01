<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::withCount('news')->get();
        return view('admin.news.news_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.news.news_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:news_categories,name|max:255']);
        NewsCategory::create($request->only('name'));
        return redirect()->route('admin.news_categories.index')->with('success', 'Node Deployed.');
    }

    public function edit(NewsCategory $category)
    {
        return view('admin.news.news_categories.edit', compact('category'));
    }

    public function update(Request $request, NewsCategory $category)
    {
        $request->validate(['name' => 'required|unique:news_categories,name,' . $category->id]);
        $category->update($request->only('name'));
        return redirect()->route('admin.news_categories.index')->with('success', 'Node Updated.');
    }

    public function destroy(NewsCategory $category)
    {
        $category->delete();
        return back();
    }
}
