<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role?->name === 'SuperAdmin' ||
               $this->user()?->role?->name === 'Admin_Unit';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'unit_id' => ['required', 'exists:units,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
