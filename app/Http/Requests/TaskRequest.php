<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $taskId = $this->route('task') ? $this->route('task')->id : null;

        return [
            'name' => "required|unique:tasks,name,{$taskId}",
            'description' => 'nullable|max:1000',
            'status_id' => 'required|integer|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|integer|exists:users,id',
            'labels' => 'nullable|array',
        ];
    }
}
