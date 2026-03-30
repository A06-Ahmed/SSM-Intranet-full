<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'matricule' => ['sometimes', 'string', 'max:50', 'unique:employees,matricule,' . $this->route('employee')->id],
            'position' => ['sometimes', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'office_location' => ['nullable', 'string', 'max:255'],
            'department_id' => ['sometimes', 'exists:departments,id'],
            'photo_url' => ['nullable', 'string', 'max:255'],
            'hire_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive,on_leave'],
        ];
    }
}
