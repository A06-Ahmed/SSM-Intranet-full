<?php

namespace App\Http\Controllers;

use App\Imports\ContactsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ContactImportController extends Controller
{
    /**
     * Show the upload form.
     */
    public function index()
    {
        return view('contacts.import');
    }

    /**
     * Handle the Excel upload and import.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xlsm'],
        ]);

        // Import only the required sheets using WithMultipleSheets.
        Excel::import(new ContactsImport(), $data['file']);

        return redirect()->back()->with('success', 'Contacts imported successfully.');
    }
}
