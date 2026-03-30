<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository
{
    public function query()
    {
        return Department::query();
    }

    public function paginate(?string $search, int $perPage, string $sortBy, string $sortDir)
    {
        $query = $this->query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }
}
