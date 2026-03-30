<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\CreateGalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Services\GalleryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function __construct(
        private readonly GalleryService $galleryService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = min((int) $request->query('per_page', 15), 100);
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $items = $this->galleryService->list($search, $perPage, $sortBy, $sortDir);

        return $this->success('Gallery retrieved', $this->paginateData($items, GalleryResource::class));
    }

    public function store(CreateGalleryRequest $request)
    {
        $images = $request->file('images', []);
        if (empty($images)) {
            $images = [$request->file('image')];
        }

        $firstUrl = null;
        $stored = [];
        foreach ($images as $image) {
            if (!$image) {
                continue;
            }
            $path = $image->store('gallery', 'public');
            if (!$firstUrl) {
                $firstUrl = $path;
            }
            $stored[] = $path;
        }

        $gallery = $this->galleryService->create([
            'title' => $request->validated()['title'],
            'description' => $request->validated()['description'] ?? null,
            'image_url' => $firstUrl,
            'uploaded_by' => $request->user()->id,
        ]);

        foreach ($stored as $url) {
            $gallery->images()->create(['image_url' => $url]);
        }

        return $this->success('Gallery image uploaded successfully', new GalleryResource($gallery->load('images')), 201);
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

        $this->galleryService->delete($gallery);

        return $this->success('Gallery image deleted successfully');
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

    private function paginateData($paginator, string $resourceClass): array
    {
        return [
            'items' => $resourceClass::collection($paginator->items()),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }
}
