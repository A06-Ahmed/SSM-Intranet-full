<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Gallery;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalNews' => News::count(),
            'totalAnnouncements' => Announcement::count(),
            'totalGalleryItems' => Gallery::count(),
        ]);
    }

    public function stats(): JsonResponse
    {
        $priorityCounts = Announcement::query()
            ->selectRaw('priority_status, COUNT(*) as total')
            ->groupBy('priority_status')
            ->pluck('total', 'priority_status')
            ->all();

        $priority = [
            'Moyenne' => (int) ($priorityCounts['Moyenne'] ?? 0),
            'Haute' => (int) ($priorityCounts['Haute'] ?? 0),
        ];

        $since = Carbon::now()->subDays(30);

        $activity = [
            'news' => News::where('created_at', '>=', $since)->count(),
            'announcements' => Announcement::where('created_at', '>=', $since)->count(),
            'gallery' => Gallery::where('created_at', '>=', $since)->count(),
        ];

        $departments = Department::query()
            ->leftJoin('users', 'departments.id', '=', 'users.department_id')
            ->select('departments.id', 'departments.name', DB::raw('COUNT(users.id) as total'))
            ->groupBy('departments.id', 'departments.name')
            ->orderBy('departments.name')
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'total' => (int) $row->total,
            ])
            ->values()
            ->all();

        return response()->json([
            'priority' => $priority,
            'activity' => $activity,
            'departments' => $departments,
        ]);
    }
}
