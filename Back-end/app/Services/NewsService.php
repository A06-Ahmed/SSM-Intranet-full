<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;

class NewsService
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly ActivityLogService $activityLogService
    ) {}

    public function list(bool $publishedOnly, ?string $search, int $perPage, string $sortBy, string $sortDir)
    {
        return $this->newsRepository->paginate($publishedOnly, $search, $perPage, $sortBy, $sortDir);
    }

    public function create(array $data): News
    {
        if (!empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (empty($data['is_published'])) {
            $data['published_at'] = null;
        }

        $news = News::create($data);

        $this->activityLogService->log(
            auth()->id(),
            'create',
            'news',
            'News created'
        );

        return $news;
    }

    public function update(News $news, array $data): News
    {
        if (array_key_exists('is_published', $data)) {
            if (!empty($data['is_published']) && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            if (empty($data['is_published'])) {
                $data['published_at'] = null;
            }
        }

        $news->update($data);

        return $news;
    }

    public function delete(News $news): void
    {
        $news->delete();
    }
}
