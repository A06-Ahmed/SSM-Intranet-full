<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);
        $roles = Role::with('permissions')->orderBy('name')->paginate($perPage);

        return $this->success('Roles retrieved', [
            'items' => $roles->items(),
            'pagination' => [
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
                'last_page' => $roles->lastPage(),
            ],
        ]);
    }

    public function store(CreateRoleRequest $request)
    {
        $role = Role::create($request->validated());

        if ($request->filled('permission_ids')) {
            $role->permissions()->sync($request->validated()['permission_ids']);
        }

        return $this->success('Role created successfully', $role->load('permissions'), 201);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        if ($request->filled('permission_ids')) {
            $role->permissions()->sync($request->validated()['permission_ids']);
        }

        return $this->success('Role updated successfully', $role->load('permissions'));
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return $this->success('Role deleted successfully');
    }
}
