<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function query()
    {
        return Employee::query()->with(['user', 'department']);
    }

    public function paginate(?string $search, ?string $department, ?string $status, int $perPage, string $sortBy, string $sortDir)
    {
        $query = $this->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('matricule', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($department) {
            if (is_numeric($department)) {
                $query->where('department_id', (int) $department);
            } else {
                $query->whereHas('department', function ($dq) use ($department) {
                    $dq->where('name', 'like', "%{$department}%");
                });
            }
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }
}
