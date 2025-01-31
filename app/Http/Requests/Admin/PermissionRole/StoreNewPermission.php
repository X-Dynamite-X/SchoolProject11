<?php

namespace App\Http\Requests\Admin\PermissionRole;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewPermission extends FormRequest
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
            "name" => "required|string|min:2|unique:permissions,name",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "The permission name is required.",
            "name.string" => "The permission name must be a string.",
            "name.min" => "The permission name must be at least 2 characters.",
            "name.unique" => "The permission name already exists.",
        ];
    }
}
