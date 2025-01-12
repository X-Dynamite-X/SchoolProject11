<?php

namespace App\Http\Requests\Chat;

use App\Models\Conversation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ConversationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()) {
            return true;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // جلب معرف المستخدم الحالي
        $user_one_id = Auth::id();
        $user_two_id = $this->input('user_two_id'); // المستخدم الآخر
    
        return [
            'user_two_id' => [
                'required',
                'exists:users,id', // التحقق من أن المستخدم الآخر موجود في جدول المستخدمين
                function ($attribute, $value, $fail) use ($user_one_id, $user_two_id) {
                    // التحقق من أن المحادثة بين المستخدمين غير موجودة مسبقًا
                    $exists = Conversation::where(function ($query) use ($user_one_id, $user_two_id) {
                        $query->where('user_one_id', $user_one_id)
                              ->where('user_two_id', $user_two_id);
                    })->orWhere(function ($query) use ($user_one_id, $user_two_id) {
                        $query->where('user_one_id', $user_two_id)
                              ->where('user_two_id', $user_one_id);
                    })->exists();
                    if ($exists) {
                        $fail('This conversation already exists between the selected users.');
                    }
                },
            ],
        ];
    }
    
    public function messages(): array
    {
        return [
            'user_two_id.required' => 'The second user is required.',
            'user_two_id.exists' => 'The selected user does not exist.',
        ];
    }
    
}
