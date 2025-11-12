<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter' => 'nullable|array',
            'filter.status_id' => 'nullable|exists:task_statuses,id',
            'filter.created_by_id' => 'nullable|exists:users,id',
            'filter.assigned_to_id' => 'nullable|exists:users,id',
        ];
    }
}
