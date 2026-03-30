<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InternalContactsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * Map each row to a Contact model.
     */
    public function model(array $row)
    {
        $name = $this->resolveName($row);

        if (!$name) {
            return null;
        }

        return new Contact([
            'name' => $name,
            'phone' => $this->value($row, ['phone', 'telephone', 'tel', 'mobile']),
            'email' => $this->value($row, ['email', 'mail']),
            'department' => $this->value($row, ['department', 'departement', 'service']),
            'company' => $this->value($row, ['company', 'societe', 'entreprise']),
            'type' => 'internal',
        ]);
    }

    /**
     * Basic row validation.
     */
    public function rules(): array
    {
        return [
            '*.email' => ['nullable', 'email'],
            '*.phone' => ['nullable', 'string', 'max:50'],
        ];
    }

    private function resolveName(array $row): ?string
    {
        $full = $this->value($row, ['name', 'nom', 'full_name', 'contact']);
        if ($full) {
            return $full;
        }

        $first = $this->value($row, ['first_name', 'prenom']);
        $last = $this->value($row, ['last_name', 'nom_famille', 'nom']);

        $combined = trim("{$first} {$last}");
        return $combined !== '' ? $combined : null;
    }

    private function value(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
                return Str::of($row[$key])->trim()->toString();
            }
        }

        return null;
    }
}
