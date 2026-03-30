<?php

namespace App\Services;

use App\Models\Gallery;
use App\Repositories\GalleryRepository;

class GalleryService
{
    public function __construct(
        private readonly GalleryRepository $galleryRepository
    ) {}

    public function list(?string $search, int $perPage, string $sortBy, string $sortDir)
    {
        return $this->galleryRepository->paginate($search, $perPage, $sortBy, $sortDir);
    }

    public function create(array $data): Gallery
    {
        return Gallery::create($data);
    }

    public function delete(Gallery $gallery): void
    {
        $gallery->delete();
    }
}
