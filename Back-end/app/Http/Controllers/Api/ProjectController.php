<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {}

    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);
        $projects = $this->projectService->list($perPage, 'created_at', 'desc');

        return $this->success('Projects retrieved', [
            'items' => ProjectResource::collection($projects->items()),
            'pagination' => [
                'current_page' => $projects->currentPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'last_page' => $projects->lastPage(),
            ],
        ]);
    }

    public function store(CreateProjectRequest $request)
    {
        $project = $this->projectService->create($request->validated(), $request->user()->id);

        return $this->success('Project created successfully', new ProjectResource($project), 201);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project = $this->projectService->update($project, $request->validated());

        return $this->success('Project updated successfully', new ProjectResource($project));
    }

    public function destroy(Project $project)
    {
        $this->projectService->delete($project);

        return $this->success('Project deleted successfully');
    }
}
