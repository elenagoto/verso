<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserType;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->editedUser());
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editedUser()),
            ],
            'type' => [
                Rule::prohibitedIf(! $this->canChangeType()),
                'nullable',
                Rule::enum(UserType::class),
            ],
        ];
    }

    private function editedUser(): User
    {
        return $this->route('user');
    }

    private function canChangeType(): bool
    {
        return $this->user()->can('changeType', $this->editedUser());
    }
}
