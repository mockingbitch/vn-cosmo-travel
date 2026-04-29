<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageUsers() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User $target */
        $target = $this->route('user');
        $targetId = $target instanceof User ? $target->id : 0;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($targetId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var User|null $target */
            $target = $this->route('user');
            if (! $target instanceof User) {
                return;
            }

            $willBeAdmin = $this->boolean('is_admin');
            if ($target->is_admin && ! $willBeAdmin && User::administratorsCount() <= 1) {
                $validator->errors()->add(
                    'is_admin',
                    __('validation.admin_must_remain'),
                );
            }
        });
    }
}
