<?php

namespace App\Repositories;

use App\Models\Gallery;

class GalleryRepository
{
    public function query()
    {
        return Gallery::query()->with(['uploader', 'images']);
    }

    public function paginate(?string $search, int $perPage, string $sortBy, string $sortDir)
    {
        $query = $this->query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }
}
