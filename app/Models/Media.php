<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'file_name',
    'file_path',
    'mime_type',
    'size',
    'alt_text',
])]
class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media';

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(MediaUsage::class);
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function displayName(int $maxLength = 60): string
    {
        return Str::limit((string) ($this->file_name ?? ''), $maxLength);
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }
}

