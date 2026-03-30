<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class AnnouncementController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }
    public function index()
    {
        $announcements = Announcement::with('author')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.form', ['announcement' => new Announcement()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'content' => ['required', 'string', 'max:255'],
            'priority_status' => ['nullable', 'in:Moyenne,Haute'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['author_id'] = $request->user()->id;
        $data['priority_status'] = $data['priority_status'] ?? 'Moyenne';
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['published_at'] = $data['is_published'] ? now() : null;

        $announcement = Announcement::create($data);
        $this->notificationService->create(
            'Nouvelle annonce',
            $announcement->title,
            'announcement',
            $announcement->id
        );

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.form', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'content' => ['required', 'string', 'max:255'],
            'priority_status' => ['nullable', 'in:Moyenne,Haute'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['priority_status'] = $data['priority_status'] ?? $announcement->priority_status ?? 'Moyenne';
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['published_at'] = $data['is_published'] ? ($announcement->published_at ?? now()) : null;

        $announcement->update($data);
        $this->notificationService->create(
            'Annonce mise à jour',
            $announcement->title,
            'announcement',
            $announcement->id
        );

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
