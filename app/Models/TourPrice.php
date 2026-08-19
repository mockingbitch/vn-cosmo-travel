<?php

namespace App\Models;

use App\Support\CurrencyFormatter;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tour_id',
    'tour_price_type_id',
    'amount',
    'currency',
    'note',
    'sort_order',
    'is_default',
])]
class TourPrice extends Model
{
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'sort_order' => 'integer',
            'is_default' => 'boolean',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function priceType(): BelongsTo
    {
        return $this->belongsTo(TourPriceType::class, 'tour_price_type_id');
    }

    /**
     * Admin-defined price type name, e.g. "Price for 2 people".
     */
    public function label(): string
    {
        return (string) ($this->priceType?->name ?? '');
    }

    public function categoryLabel(): string
    {
        return $this->priceType?->categoryLabel() ?? __('admin.price_types.uncategorized');
    }

    public function formattedAmount(): string
    {
        return CurrencyFormatter::format(
            (int) $this->amount,
            filled($this->currency) ? (string) $this->currency : Tour::CURRENCY_USD
        );
    }
}
