<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Announcements\CreateAnnouncementRequest;
use App\Http\Requests\Announcements\UpdateAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(
        private readonly AnnouncementService $announcementService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $publishedOnly = !$user || $user->hasRole('Employee');

        $search = $request->query('search');
        $perPage = min((int) $request->query('per_page', 15), 100);
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $announcements = $this->announcementService->list($publishedOnly, $search, $perPage, $sortBy, $sortDir);

        return $this->success('Announcements retrieved', $this->paginateData($announcements, AnnouncementResource::class));
    }

    public function show(Announcement $announcement, Request $request)
    {
        $user = $request->user();
        $isEmployee = !$user || $user->hasRole('Employee');

        if ($isEmployee && !$announcement->is_published) {
            return $this->error('Forbidden', null, 403);
        }

        return $this->success('Announcement retrieved', new AnnouncementResource($announcement->load('author')));
    }

    public function store(CreateAnnouncementRequest $request)
    {
        $data = array_merge($request->validated(), [
            'author_id' => $request->user()->id,
        ]);

        $announcement = $this->announcementService->create($data);

        return $this->success('Announcement created successfully', new AnnouncementResource($announcement->load('author')), 201);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $announcement = $this->announcementService->update($announcement, $request->validated());

        return $this->success('Announcement updated successfully', new AnnouncementResource($announcement->load('author')));
    }

    public function destroy(Announcement $announcement)
    {
        $this->announcementService->delete($announcement);

        return $this->success('Announcement deleted successfully');
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
