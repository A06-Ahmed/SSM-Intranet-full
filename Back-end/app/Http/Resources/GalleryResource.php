<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class GalleryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $images = $this->images->pluck('image_url')->all();
        if (empty($images) && $this->image_url) {
            $images = [$this->image_url];
        }

        $images = array_values(array_filter(array_map(fn ($url) => $this->normalizeUrl($url), $images)));

        $cover = $this->normalizeUrl($this->image_url ?: ($images[0] ?? null));

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->normalizeUrl($this->image_url),
            'cover_image' => $cover,
            'images' => $images,
            'uploaded_by' => $this->uploaded_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function normalizeUrl(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $path = parse_url($value, PHP_URL_PATH) ?: $value;
        $filename = basename($path);
        if (!$filename) {
            return null;
        }

        return url('/gallery-image/'.$filename);
    }
}
