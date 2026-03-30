<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\CreateEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\BaseContactExterne;
use App\Models\BaseContactInterne;
use App\Models\Contact;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        return $this->success('Employees retrieved', $this->paginateData($employees, EmployeeResource::class));
    }

    public function publicIndex(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $limit = (int) $request->query('limit', 0);
        if ($limit < 0) {
            $limit = 0;
        }
        if ($limit > 200) {
            $limit = 200;
        }

        $cacheKey = ($search !== '' || $limit)
            ? 'public_contacts_'.md5($search.'|'.$limit)
            : 'public_contacts';

        $ttl = $search !== '' ? 120 : 300;

        $items = Cache::remember($cacheKey, $ttl, function () use ($search, $limit) {
            $like = $this->buildLikePattern($search);

            $internesQuery = BaseContactInterne::query()
                ->select([
                    'id',
                    'nom_et_prenom',
                    'affectation',
                    'site',
                    'extension',
                    'telephone_portable',
                    'code',
                    'courriel',
                    'matricule',
                ]);

            if ($search !== '') {
                $internesQuery->where(function ($query) use ($like) {
                    $query
                        ->where('nom_et_prenom', 'like', $like)
                        ->orWhere('affectation', 'like', $like)
                        ->orWhere('site', 'like', $like)
                        ->orWhere('courriel', 'like', $like)
                        ->orWhere('telephone_portable', 'like', $like)
                        ->orWhere('matricule', 'like', $like)
                        ->orWhere('code', 'like', $like)
                        ->orWhere('extension', 'like', $like);
                });
            }

            $internes = $internesQuery
                ->orderBy('nom_et_prenom')
                ->get()
                ->map(function (BaseContactInterne $contact) {
                    return [
                        'id' => 'interne-'.$contact->id,
                        'name' => $contact->nom_et_prenom ?? '',
                        'affectation' => $contact->affectation ?? '',
                        'department' => $contact->site ?? '',
                        'email' => $contact->courriel ?? '',
                        'phone' => $contact->telephone_portable ?? '',
                        'code' => $contact->code ?? '',
                        'extension' => $contact->extension ?? '',
                        'matricule' => $contact->matricule ?? '',
                        'type' => 'interne',
                    ];
                });

            $externesQuery = BaseContactExterne::query()
                ->select([
                    'id',
                    'organisation',
                    'inerlocuteur',
                    'telephone_portable',
                    'code',
                    'ligne_fixe',
                    'courriel',
                ]);

            if ($search !== '') {
                $externesQuery->where(function ($query) use ($like) {
                    $query
                        ->where('organisation', 'like', $like)
                        ->orWhere('inerlocuteur', 'like', $like)
                        ->orWhere('courriel', 'like', $like)
                        ->orWhere('telephone_portable', 'like', $like)
                        ->orWhere('ligne_fixe', 'like', $like)
                        ->orWhere('code', 'like', $like);
                });
            }

            $externes = $externesQuery
                ->orderBy('organisation')
                ->get()
                ->map(function (BaseContactExterne $contact) {
                    return [
                        'id' => 'externe-'.$contact->id,
                        'name' => $contact->inerlocuteur ?? $contact->organisation ?? '',
                        'affectation' => '',
                        'department' => $contact->organisation ?? '',
                        'email' => $contact->courriel ?? '',
                        'phone' => $contact->telephone_portable ?? $contact->ligne_fixe ?? '',
                        'code' => $contact->code ?? '',
                        'extension' => '',
                        'matricule' => '',
                        'type' => 'externe',
                    ];
                });

            $contactsQuery = Contact::query()
                ->select(['id', 'name', 'phone', 'email', 'department', 'company']);

            if ($search !== '') {
                $contactsQuery->where(function ($query) use ($like) {
                    $query
                        ->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('department', 'like', $like)
                        ->orWhere('company', 'like', $like);
                });
            }

            $contacts = $contactsQuery
                ->orderBy('name')
                ->get()
                ->map(function (Contact $contact) {
                    $department = $contact->department ?: ($contact->company ?? '');

                    return [
                        'id' => 'contact-'.$contact->id,
                        'name' => $contact->name ?? '',
                        'affectation' => $department,
                        'department' => $department,
                        'email' => $contact->email ?? '',
                        'phone' => $contact->phone ?? '',
                        'code' => '',
                        'extension' => '',
                        'matricule' => '',
                        'type' => 'contact',
                    ];
                });

            $employeesQuery = Employee::query()->with(['user', 'department']);
            if ($search !== '') {
                $employeesQuery->where(function ($query) use ($like) {
                    $query
                        ->where('matricule', 'like', $like)
                        ->orWhere('position', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhereHas('user', function ($userQuery) use ($like) {
                            $userQuery
                                ->where('first_name', 'like', $like)
                                ->orWhere('last_name', 'like', $like)
                                ->orWhere('email', 'like', $like);
                        })
                        ->orWhereHas('department', function ($deptQuery) use ($like) {
                            $deptQuery->where('name', 'like', $like);
                        });
                });
            }

            $employees = $employeesQuery
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function (Employee $employee) {
                    $name = trim(($employee->user->first_name ?? '').' '.($employee->user->last_name ?? ''));

                    return [
                        'id' => 'employee-'.$employee->id,
                        'name' => $name !== '' ? $name : ($employee->matricule ?? ''),
                        'affectation' => $employee->position ?? '',
                        'department' => $employee->department?->name ?? '',
                        'email' => $employee->user?->email ?? '',
                        'phone' => $employee->phone ?? '',
                        'code' => '',
                        'extension' => '',
                        'matricule' => $employee->matricule ?? '',
                        'type' => 'interne',
                    ];
                });

            $merged = $internes
                ->concat($externes)
                ->concat($contacts)
                ->concat($employees)
                ->sortBy(function (array $item) {
                    return mb_strtolower($item['name'] ?? '');
                })
                ->values();

            if ($limit > 0) {
                return $merged->take($limit)->values();
            }

            return $merged;
        });

        return $this->success('Contacts retrieved', [
            'items' => $items,
            'pagination' => [
                'current_page' => 1,
                'per_page' => $items->count(),
                'total' => $items->count(),
                'last_page' => 1,
            ],
        ]);
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department']);

        return $this->success('Employee retrieved', new EmployeeResource($employee));
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

    private function buildLikePattern(string $search): string
    {
        if ($search === '') {
            return '%';
        }

        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);
        return '%'.$escaped.'%';
    }
}
