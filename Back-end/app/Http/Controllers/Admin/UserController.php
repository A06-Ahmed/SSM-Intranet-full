<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->roleOptions();
        $departments = Department::orderBy('name')->get();

        return view('admin.users.form', [
            'user' => new User(),
            'roles' => $roles,
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'is_active' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $user = User::create($data);

        $this->syncRoles($request, $user);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User created successfully.',
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'department_id' => $user->department_id,
                ],
            ], 201);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = $this->roleOptions();
        $departments = Department::orderBy('name')->get();

        return view('admin.users.form', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'is_active' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $user->update($data);

        $this->syncRoles($request, $user);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    private function roleOptions()
    {
        return Role::whereIn('name', ['Admin', 'HR', 'Manager', 'Employee'])->orderBy('name')->get();
    }

    private function syncRoles(Request $request, User $user): void
    {
        if (!$request->user()->hasRole('SuperAdmin')) {
            if (!$user->roles()->exists()) {
                $employeeRole = Role::where('name', 'Employee')->first();
                if ($employeeRole) {
                    $user->roles()->sync([$employeeRole->id]);
                }
            }
            return;
        }

        $roles = collect($request->input('roles', []))
            ->filter()
            ->values()
            ->all();

        if (empty($roles)) {
            $employeeRole = Role::where('name', 'Employee')->first();
            if ($employeeRole) {
                $user->roles()->sync([$employeeRole->id]);
            }
            return;
        }

        $roleIds = Role::whereIn('name', $roles)->pluck('id')->all();
        $user->roles()->sync($roleIds);
    }
}
