<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\CreateEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $employeeService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $department = $request->query('department');
        $status = $request->query('status');
        $perPage = min((int) $request->query('per_page', 15), 100);
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $employees = $this->employeeService->list($search, $department, $status, $perPage, $sortBy, $sortDir);

        return $this->success('Employees retrieved', [
            'items' => EmployeeResource::collection($employees->items()),
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'last_page' => $employees->lastPage(),
            ],
        ]);
    }

    public function store(CreateEmployeeRequest $request)
    {
        $employee = $this->employeeService->create($request->validated());

        return $this->success('Employee created successfully', new EmployeeResource($employee->load(['user', 'department'])), 201);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee = $this->employeeService->update($employee, $request->validated());

        return $this->success('Employee updated successfully', new EmployeeResource($employee->load(['user', 'department'])));
    }

    public function destroy(Employee $employee)
    {
        $this->employeeService->delete($employee);

        return $this->success('Employee deleted successfully');
    }
}
