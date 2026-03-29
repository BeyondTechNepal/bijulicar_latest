<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsArticle;
use Illuminate\Support\Facades\Storage;

class NewsArticleController extends Controller
{
    public function index()
    {
        $articles = NewsArticle::orderBy('published_at', 'asc')->paginate(5);
        return view('admin.news.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateArticle($request);

        // Uploads
        $data['author_image'] = $this->uploadImage($request, 'author_image');
        $data['hero_image'] = $this->uploadImage($request, 'hero_image');

        // Arrays
        $data['vehicle_specs'] = $this->mapSpecs($request);
        $data['related_articles'] = $this->mapRelated($request);
        $data['content_images'] = $this->cleanSimpleArray($request->input('content_images'));
        $data['content_images2'] = $this->cleanSimpleArray($request->input('content_images2'));

        NewsArticle::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News article created successfully.');
    }

    public function edit(NewsArticle $news)
    {
        return view('admin.news.edit', ['article' => $news]);
    }

    public function update(Request $request, NewsArticle $news)
    {
        $data = $this->validateArticle($request);

        // Uploads with cleanup
        $data['author_image'] = $this->updateImage($request, $news, 'author_image');
        $data['hero_image'] = $this->updateImage($request, $news, 'hero_image');

        // Arrays
        $data['vehicle_specs'] = $this->mapSpecs($request);
        $data['related_articles'] = $this->mapRelated($request);
        $data['content_images'] = $this->cleanSimpleArray($request->input('content_images'));
        $data['content_images2'] = $this->cleanSimpleArray($request->input('content_images2'));

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'News article updated successfully.');
    }

    public function destroy(NewsArticle $news)
    {
        if ($news->author_image) Storage::disk('public')->delete($news->author_image);
        if ($news->hero_image) Storage::disk('public')->delete($news->hero_image);

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News article deleted successfully.');
    }

    // ================= VALIDATION =================
    protected function validateArticle(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'read_time' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',

            'hero_caption' => 'nullable|string',
            'content_html' => 'nullable|string',
            'quote' => 'nullable|string',

            // Section 2
            'title2' => 'nullable|string|max:255',
            'content_html2' => 'nullable|string',

            // Section 3
            'title3' => 'nullable|string|max:255',
            'content_html3' => 'nullable|string',

            // Arrays
            'vehicle_specs' => 'nullable|array',
            'content_images' => 'nullable|array',
            'content_images2' => 'nullable|array',
            'related_articles' => 'nullable|array',

            // Images
            'author_image' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
        ]);
    }

    // ================= HELPERS =================

    protected function uploadImage(Request $request, $field)
    {
        return $request->hasFile($field)
            ? $request->file($field)->store('news_images', 'public')
            : null;
    }

    protected function updateImage(Request $request, $model, $field)
    {
        if ($request->hasFile($field)) {
            if ($model->$field) {
                Storage::disk('public')->delete($model->$field);
            }
            return $request->file($field)->store('news_images', 'public');
        }

        return $model->$field;
    }

    protected function mapSpecs(Request $request)
    {
        return collect($request->input('vehicle_specs.key', []))
            ->map(fn($key, $i) => [
                'key' => $key,
                'value' => $request->input('vehicle_specs.value')[$i] ?? ''
            ])
            ->filter(fn($item) => !empty($item['key']))
            ->values()
            ->toArray();
    }

    protected function mapRelated(Request $request)
    {
        return collect($request->input('related_articles.title', []))
            ->map(fn($title, $i) => [
                'title' => $title,
                'category' => $request->input('related_articles.category')[$i] ?? '',
                'image' => $request->input('related_articles.image')[$i] ?? '',
            ])
            ->filter(fn($item) => !empty($item['title']))
            ->values()
            ->toArray();
    }

    protected function cleanSimpleArray($array)
    {
        return collect($array ?? [])
            ->filter(fn($item) => !empty($item))
            ->values()
            ->toArray();
    }
}