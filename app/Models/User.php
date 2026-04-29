<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin', 'can_access_panel', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_DISABLED = 'disabled';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_admin' => 'boolean',
            'can_access_panel' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function canAccessAdmin(): bool
    {
        return (bool) $this->can_access_panel;
    }

    /** Full administrators may manage staff accounts (create/edit/delete users). */
    public function canManageUsers(): bool
    {
        return (bool) $this->is_admin;
    }

    public static function administratorsCount(): int
    {
        return static::query()->where('is_admin', true)->where('status', self::STATUS_ACTIVE)->count();
    }
}
