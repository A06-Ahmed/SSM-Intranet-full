<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('base_contacts_internes') || !Schema::hasTable('employees')) {
            return;
        }

        $existingEmails = DB::table('base_contacts_internes')
            ->whereNotNull('courriel')
            ->pluck('courriel')
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter()
            ->values()
            ->all();

        $existingMatricules = DB::table('base_contacts_internes')
            ->whereNotNull('matricule')
            ->pluck('matricule')
            ->map(fn ($matricule) => strtolower(trim((string) $matricule)))
            ->filter()
            ->values()
            ->all();

        $employees = DB::table('employees')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select([
                'employees.matricule',
                'employees.position',
                'employees.phone',
                'employees.office_location',
                'users.first_name',
                'users.last_name',
                'users.email',
                'departments.name as department_name',
            ])
            ->get();

        foreach ($employees as $employee) {
            $email = $employee->email ? strtolower(trim((string) $employee->email)) : null;
            $matricule = $employee->matricule ? strtolower(trim((string) $employee->matricule)) : null;

            if ($email && in_array($email, $existingEmails, true)) {
                continue;
            }

            if ($matricule && in_array($matricule, $existingMatricules, true)) {
                continue;
            }

            $fullName = trim((string) $employee->first_name.' '.(string) $employee->last_name);
            if ($fullName === '') {
                $fullName = $employee->matricule ?? '';
            }

            DB::table('base_contacts_internes')->insert([
                'nom_et_prenom' => $fullName ?: null,
                'affectation' => $employee->position ?: $employee->department_name,
                'site' => $employee->office_location ?: $employee->department_name,
                'extension' => null,
                'telephone_portable' => $employee->phone,
                'code' => null,
                'fixe_directe' => null,
                'courriel' => $employee->email,
                'matricule' => $employee->matricule,
            ]);
        }
    }

    public function down(): void
    {
        // One-way migration: no rollback to avoid deleting existing data.
    }
};
