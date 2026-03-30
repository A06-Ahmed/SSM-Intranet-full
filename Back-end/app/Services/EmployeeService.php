<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;

class EmployeeService
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly ActivityLogService $activityLogService
    ) {}

    public function list(?string $search, ?string $department, ?string $status, int $perPage, string $sortBy, string $sortDir)
    {
        return $this->employeeRepository->paginate($search, $department, $status, $perPage, $sortBy, $sortDir);
    }

    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    public function update(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee;
    }

    public function delete(Employee $employee): void
    {
        $employee->delete();

        $this->activityLogService->log(
            auth()->id(),
            'delete',
            'employees',
            'Employee deleted'
        );
    }
}
