<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;

 use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        // return auth()->user()->hasRole("admin");
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
            'user_ids' => 'required|array',
            'user_ids.*' => 'numeric|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_ids.required' => 'Please select at least one user.',
            'user_ids.array' => 'The user selection must be an array.',
            'user_ids.*.numeric' => 'Please select a valid user.',
            'user_ids.*.exists' => 'The selected user does not exist.',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user_ids = $this->input('user_ids');
            $subject_id = $this->subject->id;

            foreach ($user_ids as $user_id) {
                $user = User::find($user_id);
                if ($user && DB::table('subject_users')->where('subject_id', $subject_id)->where('user_id', $user_id)->exists()) {
                    $validator->errors()->add(
                        "user_ids.$user_id",
                        "The user '{$user->name}' is already in the subject."
                    );
                }
            }
        });
    }
}
