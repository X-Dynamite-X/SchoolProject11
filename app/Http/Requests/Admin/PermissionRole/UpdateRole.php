<?php

namespace App\Http\Requests\Admin\PermissionRole;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRole extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // التحقق من أن المستخدم مسجل الدخول ولديه دور "admin"
        return auth()->check() && auth()->user()->hasRole("admin");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // التحقق من أن النموذج موجود
        if (!isset($this->role) || !$this->role->id) {
            abort(404, "Role not found.");
        }

        return [
            // التحقق من أن الاسم مطلوب، نص، لا يقل عن حرفين، ولا يتكرر (مع استثناء السجل الحالي)
            "name" => "required|string|min:2|unique:roles,name," . $this->role->id,

            // التحقق من أن الصلاحيات مطلوبة، وتكون مصفوفة تحتوي على عنصر واحد على الأقل
            "permissions" => "required|array|min:1",

            // التحقق من أن كل عنصر في مصفوفة الصلاحيات موجود في جدول الصلاحيات
            "permissions.*" => "exists:permissions,name",
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // رسائل الخطأ الخاصة بحقل "name"
            "name.required" => "The role name is required.",
            "name.string" => "The role name must be a valid string.",
            "name.min" => "The role name must be at least 2 characters long.",
            "name.unique" => "This role name already exists. Please choose a different one.",

            // رسائل الخطأ الخاصة بحقل "permissions"
            "permissions.required" => "At least one permission must be selected.",
            "permissions.array" => "The permissions must be provided as an array.",
            "permissions.min" => "You must select at least one permission.",
            "permissions.*.exists" => "One or more selected permissions are invalid.",
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->getMessages();

        // تغيير مفتاح الخطأ من "name" إلى "updateName" لتسهيل التعامل مع الأخطاء في الجهة الأمامية
        if (isset($errors["name"])) {
            $errors["updateName"] = $errors["name"];
            unset($errors["name"]);
        }

        // إرجاع رسالة خطأ بتنسيق JSON مع رمز الحالة 422
        throw new HttpResponseException(response()->json([
            "errors" => $errors
        ], 422));
    }
}
