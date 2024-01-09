<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsenceRequest extends FormRequest
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
        return [
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason_id' => 'required|integer|exists:reasons,id',
            'file' => 'nullable|mimes:jpg,jpeg,png,doc,docx,pdf|max:3072',
            'status_detail' => 'nullable|string',
            'worker_id' => 'required|integer|exists:workers,id',
        ];
    }
}
