<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function __construct(
        private readonly NewsService $newsService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $publishedOnly = !$user || $user->hasRole('Employee');

        $search = $request->query('search');
        $perPage = min((int) $request->query('per_page', 15), 100);
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $news = $this->newsService->list($publishedOnly, $search, $perPage, $sortBy, $sortDir);

        return $this->success('News retrieved', $this->paginateData($news, NewsResource::class));
    }

    public function show(News $news, Request $request)
    {
        $user = $request->user();
        $isEmployee = !$user || $user->hasRole('Employee');

        if ($isEmployee && !$news->is_published) {
            return $this->error('Forbidden', null, 403);
        }

        return $this->success('News retrieved', new NewsResource($news->load('author')));
    }

    public function store(CreateNewsRequest $request)
    {
        $this->authorize('create', News::class);

        $data = array_merge($request->validated(), [
            'author_id' => $request->user()->id,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image_url'] = $path;
        }

        $news = $this->newsService->create($data);

        return $this->success('News created successfully', new NewsResource($news->load('author')), 201);
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $this->authorize('update', $news);

        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($news->image_url) {
                $existingPath = $this->resolveNewsPath($news->image_url);
                if ($existingPath) {
                    Storage::disk('public')->delete($existingPath);
                }
            }
            $path = $request->file('image')->store('news', 'public');
            $data['image_url'] = $path;
        }

        $news = $this->newsService->update($news, $data);

        return $this->success('News updated successfully', new NewsResource($news->load('author')));
    }

    public function destroy(News $news)
    {
        $this->authorize('delete', $news);

        $this->newsService->delete($news);

        return $this->success('News deleted successfully');
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

    private function resolveNewsPath(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $path = parse_url($value, PHP_URL_PATH) ?: $value;

        if (str_contains($path, '/news-image/')) {
            return 'news/'.basename($path);
        }

        if (str_starts_with($path, 'news/')) {
            return $path;
        }

        if (str_contains($path, '/storage/')) {
            return ltrim(str_replace('/storage/', '', $path), '/');
        }

        return 'news/'.basename($path);
    }
}
