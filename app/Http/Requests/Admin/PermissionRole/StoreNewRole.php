<?php

namespace App\Http\Requests\Admin\PermissionRole;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewRole extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole("admin");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|min:2|unique:roles,name",
            "permissions" => "required|array|min:1",
            "permissions.*" => "exists:permissions,name", // التحقق من أن كل عنصر في المصفوفة موجود في جدول الصلاحيات
        ];
    }
    
    public function messages(): array
    {
        return [
            // Error messages for the "name" field
            "name.required" => "The role name is required.",
            "name.string" => "The role name must be a valid string.",
            "name.min" => "The role name must be at least 2 characters long.",
            "name.unique" => "This role name already exists. Please choose a different one.",
    
            // Error messages for the "permissions" field
            "permissions.required" => "At least one permission must be selected.",
            "permissions.array" => "The permissions must be provided as an array.",
            "permissions.min" => "You must select at least one permission.",
            "permissions.*.exists" => "One or more selected permissions are invalid.",
        ];
    }
    
}
