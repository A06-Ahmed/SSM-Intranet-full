<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class GalleryController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }
    public function index()
    {
        $items = Gallery::with(['uploader', 'images'])->orderByDesc('created_at')->paginate(15);

        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        if (request()->user()->hasRole('HR') || request()->user()->hasRole('RH')) {
            abort(403);
        }
        return view('admin.gallery.form', ['item' => new Gallery()]);
    }

    public function store(Request $request)
    {
        if ($request->user()->hasRole('HR') || $request->user()->hasRole('RH')) {
            abort(403);
        }
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $firstUrl = null;
        $stored = [];
        foreach ($request->file('images', []) as $image) {
            $path = $image->store('gallery', 'public');
            if (!$firstUrl) {
                $firstUrl = $path;
            }
            $stored[] = $path;
        }

        $gallery = Gallery::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'image_url' => $firstUrl,
            'uploaded_by' => $request->user()->id,
        ]);

        foreach ($stored as $url) {
            $gallery->images()->create(['image_url' => $url]);
        }

        $this->notificationService->create(
            'Nouvelle galerie',
            $gallery->title,
            'gallery',
            $gallery->id
        );

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image uploaded.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.form', ['item' => $gallery]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $gallery->title = $data['title'];
        $gallery->description = $data['description'] ?? $gallery->description;

        if ($request->hasFile('images')) {
            foreach ($request->file('images', []) as $image) {
                $path = $image->store('gallery', 'public');
                $gallery->images()->create(['image_url' => $path]);
                if (!$gallery->image_url) {
                    $gallery->image_url = $path;
                }
            }
        }

        $gallery->save();

        $this->notificationService->create(
            'Galerie mise à jour',
            $gallery->title,
            'gallery',
            $gallery->id
        );

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->load('images');
        foreach ($gallery->images as $image) {
            $relativePath = $this->resolveGalleryPath($image->image_url);
            if ($relativePath) {
                Storage::disk('public')->delete($relativePath);
            }
        }
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted.');
    }

    private function resolveGalleryPath(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if (Str::contains($value, '/storage/')) {
            return Str::after($value, '/storage/');
        }

        if (Str::contains($value, '/gallery-image/')) {
            return 'gallery/'.basename($value);
        }

        if (Str::startsWith($value, 'gallery/')) {
            return $value;
        }

        return 'gallery/'.basename($value);
    }
}
