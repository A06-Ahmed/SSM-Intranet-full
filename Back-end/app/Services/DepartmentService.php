<?php

namespace App\Services;

use App\Models\Department;
use App\Repositories\DepartmentRepository;

class DepartmentService
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository
    ) {}

    public function list(?string $search, int $perPage, string $sortBy, string $sortDir)
    {
        return $this->departmentRepository->paginate($search, $perPage, $sortBy, $sortDir);
    }

    public function create(array $data): Department
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data): Department
    {
        $department->update($data);

        return $department;
    }

    public function delete(Department $department): void
    {
        $department->delete();
    }
}
