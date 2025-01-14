<?php

namespace App\Http\Requests\Chat;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TextMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return Auth::check();
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
            "conversation_id" => ["required", "exists:conversations,id"],
            "text" => ["required", "max:2800"]
        ];
    }
    public  function messages(): array
    {
        return [
            "conversation_id.required" => "Conversation ID is required",
            "conversation_id.exists" => "Conversation ID is not valid",
            "text.required" => "Text is required",
            "text.max" => "Text is too long"

        ];
    }
}
