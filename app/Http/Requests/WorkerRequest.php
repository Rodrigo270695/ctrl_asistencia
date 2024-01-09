<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|min:3|max:30',
            'lastname' => 'required|min:3|max:30',
            'document_type' => ['required', Rule::in(['DNI', 'CARNET DE EXTRANJERIA', 'OTROS'])],
            'pdv_id' => 'required',
            'entry_date' => 'required',
            'charge' => 'required',
            'email' => 'nullable|email',
        ];

        if ($this->document_type === 'DNI') {
            $rules['num_document'] = 'required|digits:8';
        } elseif ($this->document_type === 'CARNET DE EXTRANJERIA') {
            $rules['num_document'] = 'required|alpha_num|max:11';
        } else {
            $rules['num_document'] = 'required|alpha_num|max:15';
        }

        return $rules;
    }
}
