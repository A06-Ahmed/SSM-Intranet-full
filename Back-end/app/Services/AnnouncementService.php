<?php

namespace App\Services;

use App\Models\Announcement;
use App\Repositories\AnnouncementRepository;

class AnnouncementService
{
    public function __construct(
        private readonly AnnouncementRepository $announcementRepository,
        private readonly ActivityLogService $activityLogService
    ) {}

    public function list(bool $publishedOnly, ?string $search, int $perPage, string $sortBy, string $sortDir)
    {
        return $this->announcementRepository->paginate($publishedOnly, $search, $perPage, $sortBy, $sortDir);
    }

    public function create(array $data): Announcement
    {
        if (!empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (empty($data['is_published'])) {
            $data['published_at'] = null;
        }

        $announcement = Announcement::create($data);

        $this->activityLogService->log(
            auth()->id(),
            'create',
            'announcements',
            'Announcement created'
        );

        return $announcement;
    }

    public function update(Announcement $announcement, array $data): Announcement
    {
        if (array_key_exists('is_published', $data)) {
            if (!empty($data['is_published']) && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            if (empty($data['is_published'])) {
                $data['published_at'] = null;
            }
        }

        $announcement->update($data);

        return $announcement;
    }

    public function delete(Announcement $announcement): void
    {
        $announcement->delete();
    }
}
