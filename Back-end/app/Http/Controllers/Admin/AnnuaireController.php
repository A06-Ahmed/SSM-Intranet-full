<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaseContactExterne;
use App\Models\BaseContactInterne;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use ZipArchive;

class AnnuaireController extends Controller
{
    public function index()
    {
        return view('admin.annuaire', [
            'departments' => Department::orderBy('name')->get(),
            'prefill' => session('annuaire_prefill', []),
        ]);
    }

    public function storeManual(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'matricule' => ['required', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:50'],
            'office_location' => ['nullable', 'string', 'max:120'],
            'hire_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive,on_leave'],
        ]);

        $department = null;
        if (!empty($data['department_id'])) {
            $department = Department::find($data['department_id']);
        }
        $departmentName = $department?->name;

        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'password' => Hash::make(Str::random(12)),
                'department_id' => $data['department_id'] ?? null,
                'is_active' => true,
            ]
        );

        if (!$user->wasRecentlyCreated) {
            $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'department_id' => $data['department_id'] ?? null,
            ]);
        }

        $contact = BaseContactInterne::firstOrNew(['courriel' => $data['email']]);
        $fullName = trim($data['first_name'].' '.$data['last_name']);
        $contact->nom_et_prenom = $fullName ?: $contact->nom_et_prenom;
        $contact->affectation = $data['position']
            ?? $departmentName
            ?? $contact->affectation;
        $contact->site = $data['office_location']
            ?? $departmentName
            ?? $contact->site;
        $contact->telephone_portable = $data['phone'] ?? $contact->telephone_portable;
        $contact->fixe_directe = $data['phone'] ?? $contact->fixe_directe;
        $contact->matricule = $data['matricule'] ?? $contact->matricule;
        $contact->save();

        Cache::forget('public_contacts');

        $this->ensureEmployeeRole($user);

        return redirect()->route('admin.annuaire')->with('success', 'Annuaire entry created.');
    }

    public function importExcel(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xlsm,csv', 'max:5120'],
        ]);

        $path = $data['file']->getRealPath();
        $extension = strtolower($data['file']->getClientOriginalExtension());

        $rows = $extension === 'csv'
            ? $this->parseCsv($path)
            : $this->parseXlsx($path);

        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $email = $row['email'] ?? null;
            if (!$email) {
                continue;
            }

            $departmentId = $this->resolveDepartmentId(
                $row['department_id']
                    ?? $row['department']
                    ?? $row['departement']
                    ?? null
            );
            $department = $departmentId ? Department::find($departmentId) : null;
            $departmentName = $department?->name;

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $row['first_name'] ?? 'User',
                    'last_name' => $row['last_name'] ?? '',
                    'password' => Hash::make(Str::random(12)),
                    'department_id' => $departmentId,
                    'is_active' => true,
                ]
            );

            if (!$user->wasRecentlyCreated) {
                $user->update([
                    'first_name' => $row['first_name'] ?? $user->first_name,
                    'last_name' => $row['last_name'] ?? $user->last_name,
                    'department_id' => $departmentId ?? $user->department_id,
                ]);
                $updated++;
            } else {
                $created++;
            }

            $contact = BaseContactInterne::firstOrNew(['courriel' => $email]);
            $fullName = trim(($row['first_name'] ?? '').' '.($row['last_name'] ?? ''));
            $contact->nom_et_prenom = $fullName ?: $contact->nom_et_prenom;
            $contact->affectation = $row['affectation']
                ?? $row['position']
                ?? $departmentName
                ?? $contact->affectation;
            $contact->site = $row['office_location']
                ?? $departmentName
                ?? $contact->site;
            $contact->telephone_portable = $row['phone']
                ?? $row['telephone_portable']
                ?? $contact->telephone_portable;
            $contact->fixe_directe = $row['fixe_directe']
                ?? $row['ligne_fixe']
                ?? $contact->fixe_directe;
            $contact->code = $row['code'] ?? $contact->code;
            $contact->extension = $row['extension'] ?? $contact->extension;
            $contact->matricule = $row['matricule'] ?? $contact->matricule;
            $contact->save();

            $this->ensureEmployeeRole($user);
        }

        Cache::forget('public_contacts');

        return redirect()->route('admin.annuaire')->with('success', "Import finished. Created: {$created}, Updated: {$updated}.");
    }

    public function list(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $needle = mb_strtolower($query);

        $internes = BaseContactInterne::query()
            ->select([
                'id',
                'nom_et_prenom',
                'affectation',
                'site',
                'telephone_portable',
                'courriel',
                'matricule',
            ])
            ->orderBy('nom_et_prenom')
            ->get()
            ->map(function (BaseContactInterne $contact) {
                return [
                    'id' => 'interne-'.$contact->id,
                    'name' => $contact->nom_et_prenom ?? '',
                    'type' => 'Interne',
                    'email' => $contact->courriel ?? '',
                    'phone' => $contact->telephone_portable ?? '',
                    'department' => $contact->site ?? '',
                    'affectation' => $contact->affectation ?? '',
                    'matricule' => $contact->matricule ?? '',
                ];
            });

        $externes = BaseContactExterne::query()
            ->select([
                'id',
                'organisation',
                'inerlocuteur',
                'telephone_portable',
                'ligne_fixe',
                'courriel',
            ])
            ->orderBy('organisation')
            ->get()
            ->map(function (BaseContactExterne $contact) {
                return [
                    'id' => 'externe-'.$contact->id,
                    'name' => $contact->inerlocuteur ?? $contact->organisation ?? '',
                    'type' => 'Externe',
                    'email' => $contact->courriel ?? '',
                    'phone' => $contact->telephone_portable ?? $contact->ligne_fixe ?? '',
                    'department' => $contact->organisation ?? '',
                    'affectation' => '',
                    'matricule' => '',
                ];
            });

        $contacts = Contact::query()
            ->select(['id', 'name', 'phone', 'email', 'department', 'company'])
            ->orderBy('name')
            ->get()
            ->map(function (Contact $contact) {
                $dept = $contact->department ?: ($contact->company ?? '');
                return [
                    'id' => 'contact-'.$contact->id,
                    'name' => $contact->name ?? '',
                    'type' => 'Contact',
                    'email' => $contact->email ?? '',
                    'phone' => $contact->phone ?? '',
                    'department' => $dept,
                    'affectation' => $dept,
                    'matricule' => '',
                ];
            });

        $employees = Employee::query()
            ->with(['user', 'department'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (Employee $employee) {
                $name = trim(($employee->user->first_name ?? '').' '.($employee->user->last_name ?? ''));
                return [
                    'id' => 'employee-'.$employee->id,
                    'name' => $name !== '' ? $name : ($employee->matricule ?? ''),
                    'type' => 'Employé',
                    'email' => $employee->user?->email ?? '',
                    'phone' => $employee->phone ?? '',
                    'department' => $employee->department?->name ?? '',
                    'affectation' => $employee->position ?? '',
                    'matricule' => $employee->matricule ?? '',
                ];
            });

        $contactsAll = $internes
            ->concat($externes)
            ->concat($contacts)
            ->concat($employees)
            ->values();

        if ($needle !== '') {
            $contactsAll = $contactsAll->filter(function (array $item) use ($needle) {
                $haystack = mb_strtolower(implode(' ', [
                    $item['name'] ?? '',
                    $item['email'] ?? '',
                    $item['phone'] ?? '',
                    $item['department'] ?? '',
                    $item['affectation'] ?? '',
                    $item['matricule'] ?? '',
                    $item['type'] ?? '',
                ]));
                return str_contains($haystack, $needle);
            })->values();
        }

        $contactsAll = $contactsAll->sortBy(function (array $item) {
            return mb_strtolower($item['name'] ?? '');
        })->values();

        return view('admin.annuaire-list', [
            'contacts' => $contactsAll,
            'query' => $query,
        ]);
    }

    private function ensureEmployeeRole(User $user): void
    {
        if ($user->roles()->exists()) {
            return;
        }

        $role = Role::where('name', 'Employee')->first();
        if ($role) {
            $user->roles()->sync([$role->id]);
        }
    }

    private function parseCsv(string $path): array
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) === false) {
            return $rows;
        }

        $headers = [];
        $line = 0;
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $line++;
            if ($line === 1) {
                $headers = array_map(fn ($h) => strtolower(trim($h)), $data);
                continue;
            }

            $row = [];
            foreach ($headers as $i => $header) {
                if (!$header) {
                    continue;
                }
                $row[$header] = $data[$i] ?? null;
            }
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function parseXlsx(string $path): array
    {
        $rows = [];
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            return $rows;
        }

        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml) {
            $shared = simplexml_load_string($sharedXml);
            foreach ($shared->si as $si) {
                $text = '';
                if (isset($si->t)) {
                    $text = (string) $si->t;
                } elseif (isset($si->r)) {
                    foreach ($si->r as $run) {
                        $text .= (string) $run->t;
                    }
                }
                $sharedStrings[] = $text;
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if (!$sheetXml) {
            $zip->close();
            return $rows;
        }

        $sheet = simplexml_load_string($sheetXml);
        $headers = [];

        foreach ($sheet->sheetData->row as $row) {
            $cells = [];
            foreach ($row->c as $c) {
                $ref = (string) $c['r'];
                $column = preg_replace('/\d+/', '', $ref);
                $index = $this->columnToIndex($column);
                $value = (string) ($c->v ?? '');
                if ((string) $c['t'] === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                }
                $cells[$index] = $value;
            }

            ksort($cells);
            $cells = array_values($cells);

            if (empty($headers)) {
                $headers = array_map(fn ($h) => strtolower(trim((string) $h)), $cells);
                continue;
            }

            $rowData = [];
            foreach ($headers as $i => $header) {
                if (!$header) {
                    continue;
                }
                $rowData[$header] = $cells[$i] ?? null;
            }
            $rows[] = $rowData;
        }

        $zip->close();

        return $rows;
    }

    private function columnToIndex(string $column): int
    {
        $column = strtoupper($column);
        $index = 0;
        for ($i = 0; $i < strlen($column); $i++) {
            $index = $index * 26 + (ord($column[$i]) - 64);
        }

        return $index - 1;
    }

    private function resolveDepartmentId(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        $name = trim((string) $value);
        if ($name === '') {
            return null;
        }

        return Department::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->value('id');
    }
}
