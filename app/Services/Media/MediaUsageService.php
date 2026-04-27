<?php

namespace App\Services\Media;

use App\Models\MediaUsage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MediaUsageService
{
    public function syncSingle(?int $mediaId, Model $model, ?string $field = null): void
    {
        DB::transaction(function () use ($mediaId, $model, $field): void {
            MediaUsage::query()
                ->where('model_type', $model::class)
                ->where('model_id', $model->getKey())
                ->when($field !== null, fn ($q) => $q->where('field', $field), fn ($q) => $q->whereNull('field'))
                ->delete();

            if ($mediaId) {
                MediaUsage::query()->create([
                    'media_id' => $mediaId,
                    'model_type' => $model::class,
                    'model_id' => (int) $model->getKey(),
                    'field' => $field,
                ]);
            }
        });
    }
}

