<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);
        $permissions = Permission::orderBy('module')->orderBy('name')->paginate($perPage);

        return $this->success('Permissions retrieved', [
            'items' => $permissions->items(),
            'pagination' => [
                'current_page' => $permissions->currentPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
                'last_page' => $permissions->lastPage(),
            ],
        ]);
    }

    public function store(CreatePermissionRequest $request)
    {
        $permission = Permission::create($request->validated());

        return $this->success('Permission created successfully', $permission, 201);
    }
}
