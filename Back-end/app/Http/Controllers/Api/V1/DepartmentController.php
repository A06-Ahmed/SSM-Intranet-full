<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Departments\CreateDepartmentRequest;
use App\Http\Requests\Departments\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(
        private readonly DepartmentService $departmentService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = min((int) $request->query('per_page', 15), 100);
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $departments = $this->departmentService->list($search, $perPage, $sortBy, $sortDir);

        return $this->success('Departments retrieved', $this->paginateData($departments, DepartmentResource::class));
    }

    public function show(Department $department)
    {
        return $this->success('Department retrieved', new DepartmentResource($department));
    }

    public function store(CreateDepartmentRequest $request)
    {
        $department = $this->departmentService->create($request->validated());

        return $this->success('Department created successfully', new DepartmentResource($department), 201);
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department = $this->departmentService->update($department, $request->validated());

        return $this->success('Department updated successfully', new DepartmentResource($department));
    }

    public function destroy(Department $department)
    {
        $this->departmentService->delete($department);

        return $this->success('Department deleted successfully');
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
