<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function list(int $perPage, string $sortBy, string $sortDir)
    {
        return Project::query()->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function create(array $data, ?int $userId): Project
    {
        $data['created_by'] = $userId;

        return Project::create($data);
    }

    public function update(Project $project, array $data): Project
    {
        $project->update($data);

        return $project;
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
