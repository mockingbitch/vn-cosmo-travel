<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserStatusRequest extends FormRequest
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
        return [
            'status' => ['required', 'string', Rule::in([User::STATUS_ACTIVE, User::STATUS_DISABLED])],
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

            $willBeDisabled = $this->input('status') === User::STATUS_DISABLED;

            if ($willBeDisabled && $target->id === auth()->id()) {
                $validator->errors()->add(
                    'status',
                    __('validation.cannot_disable_self'),
                );
            }

            if ($willBeDisabled && $target->is_admin && $target->isActive() && User::administratorsCount() <= 1) {
                $validator->errors()->add(
                    'status',
                    __('validation.admin_must_remain_active'),
                );
            }
        });
    }
}
