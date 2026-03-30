<?php

namespace App\Repositories;

use App\Models\Announcement;

class AnnouncementRepository
{
    public function query()
    {
        return Announcement::query()->with('author');
    }

    public function paginate(bool $publishedOnly, ?string $search, int $perPage, string $sortBy, string $sortDir)
    {
        $query = $this->query();

        if ($publishedOnly) {
            $query->where('is_published', true);
        }

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }
}
