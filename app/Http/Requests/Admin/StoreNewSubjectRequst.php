<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewSubjectRequst extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole("admin");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|max:15',
            'success_mark' => 'required|numeric',
            'full_mark' => 'required|numeric',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required',
            'success_mark.required' => 'The Success Mark field is required',
            'full_mark.required' => 'The Full Mark field is required',
        ];
    }
}
