<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ContactsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            // Map specific sheet names to their importers.
            'Base Contacts Internes' => new InternalContactsImport(),
            'Base Contacts Externes' => new ExternalContactsImport(),
        ];
    }
}
