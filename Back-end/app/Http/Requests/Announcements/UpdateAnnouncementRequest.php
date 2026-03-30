<?php

namespace App\Http\Requests\Announcements;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'content' => ['required', 'string', 'max:255'],
            'priority_status' => ['nullable', 'in:Moyenne,Haute'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
