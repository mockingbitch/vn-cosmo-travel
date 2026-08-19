<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Support\CurrencyFormatter;
use Illuminate\Contracts\Validation\Validator;

/**
 * Shared handling of the repeatable "one tour, many prices" rows.
 */
trait HandlesTourPriceRows
{
    /**
     * Drops blank rows, reduces amounts to plain integers, and re-points
     * `default_price_index` at the row it pointed to before compaction.
     */
    protected function normalizePriceRows(): void
    {
        $raw = $this->input('prices');

        if (! is_array($raw)) {
            $this->merge(['prices' => [], 'default_price_index' => 0]);

            return;
        }

        $submittedDefault = (int) $this->input('default_price_index', 0);
        $defaultIndex = 0;

        $rows = [];
        foreach ($raw as $originalIndex => $row) {
            if (! is_array($row)) {
                continue;
            }

            $typeId = trim((string) ($row['tour_price_type_id'] ?? ''));
            $amount = CurrencyFormatter::parse(
                isset($row['amount']) && is_scalar($row['amount']) ? (string) $row['amount'] : null
            );
            $note = trim((string) ($row['note'] ?? ''));

            if ($typeId === '' && $amount === null && $note === '') {
                continue;
            }

            if ((int) $originalIndex === $submittedDefault) {
                $defaultIndex = count($rows);
            }

            $rows[] = [
                'tour_price_type_id' => $typeId === '' ? null : (int) $typeId,
                'amount' => $amount,
                'note' => $note === '' ? null : $note,
            ];
        }

        $this->merge([
            'prices' => $rows,
            'default_price_index' => $defaultIndex,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function priceRowRules(): array
    {
        return [
            'prices' => ['required', 'array', 'min:1'],
            'prices.*.tour_price_type_id' => ['required', 'integer', 'exists:tour_price_types,id'],
            'prices.*.amount' => ['required', 'integer', 'min:0'],
            'prices.*.note' => ['nullable', 'string', 'max:255'],
            'default_price_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function priceRowMessages(): array
    {
        return [
            'prices.required' => __('validation.tour_prices.required'),
            'prices.min' => __('validation.tour_prices.required'),
            'prices.*.tour_price_type_id.required' => __('validation.tour_prices.type_required'),
            'prices.*.tour_price_type_id.exists' => __('validation.tour_prices.type_required'),
            'prices.*.amount.required' => __('validation.tour_prices.amount_required'),
        ];
    }

    /**
     * A tour may not price the same type twice — that reading is ambiguous.
     */
    protected function validatePriceRowTypesAreUnique(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $rows = $this->input('prices');
            if (! is_array($rows)) {
                return;
            }

            $seen = [];
            foreach ($rows as $index => $row) {
                $typeId = is_array($row) ? ($row['tour_price_type_id'] ?? null) : null;
                if ($typeId === null || $typeId === '') {
                    continue;
                }

                $typeId = (int) $typeId;
                if (in_array($typeId, $seen, true)) {
                    $validator->errors()->add(
                        'prices.'.$index.'.tour_price_type_id',
                        __('validation.tour_prices.duplicate_type')
                    );

                    continue;
                }

                $seen[] = $typeId;
            }
        });
    }
}
