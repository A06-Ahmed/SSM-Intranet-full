<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }
    public function index()
    {
        $newsItems = News::with('author')->orderByDesc('created_at')->paginate(15);

        return view('admin.news.index', compact('newsItems'));
    }

    public function create()
    {
        if (request()->user()->hasRole('HR') || request()->user()->hasRole('RH')) {
            abort(403);
        }
        return view('admin.news.form', ['news' => new News()]);
    }

    public function store(Request $request)
    {
        if ($request->user()->hasRole('HR') || $request->user()->hasRole('RH')) {
            abort(403);
        }
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['author_id'] = $request->user()->id;
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['published_at'] = $data['is_published'] ? now() : null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image_url'] = $path;
        }

        $news = News::create($data);
        $this->notificationService->create(
            'Nouvelle actualité',
            $news->title,
            'news',
            $news->id
        );

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        return view('admin.news.form', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['published_at'] = $data['is_published'] ? ($news->published_at ?? now()) : null;

        if ($request->hasFile('image')) {
            if ($news->image_url) {
                $relativePath = $this->resolveNewsPath($news->image_url);
                if ($relativePath) {
                    Storage::disk('public')->delete($relativePath);
                }
            }
            $path = $request->file('image')->store('news', 'public');
            $data['image_url'] = $path;
        }

        $news->update($data);
        $this->notificationService->create(
            'Actualité mise à jour',
            $news->title,
            'news',
            $news->id
        );

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        if ($news->image_url) {
            $relativePath = $this->resolveNewsPath($news->image_url);
            if ($relativePath) {
                Storage::disk('public')->delete($relativePath);
            }
        }
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }

    private function resolveNewsPath(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if (Str::contains($value, '/storage/')) {
            return Str::after($value, '/storage/');
        }

        if (Str::contains($value, '/news-image/')) {
            return 'news/'.basename($value);
        }

        if (Str::startsWith($value, 'news/')) {
            return $value;
        }

        return 'news/'.basename($value);
    }
}
