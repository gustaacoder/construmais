<?php

namespace App\DTOs;

class SaleTotalsDTO
{
    public function __construct(
        public readonly float $subtotal,
        public readonly float $discountTotal,
        public readonly float $surchargeTotal,
        public readonly float $grandTotal
    ) {}

    /**
     * Create from sale items and adjustment values
     *
     * @param  array  $items  Array of sale items
     * @param  float  $discountTotal  Total discount amount
     * @param  float  $surchargeTotal  Total surcharge amount
     */
    public static function fromSaleData(
        array $items,
        float $discountTotal = 0.0,
        float $surchargeTotal = 0.0
    ): self {
        $subtotal = 0.0;

        foreach ($items as $item) {
            $quantity = (int) ($item['quantity'] ?? $item->quantity ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? $item->unit_price ?? 0);
            $discount = (float) ($item['discount'] ?? $item->discount ?? 0);

            $lineTotal = max(0, ($quantity * $unitPrice) - $discount);
            $subtotal += $lineTotal;
        }

        $grandTotal = max(0, $subtotal - $discountTotal + $surchargeTotal);

        return new self(
            subtotal: round($subtotal, 2),
            discountTotal: round($discountTotal, 2),
            surchargeTotal: round($surchargeTotal, 2),
            grandTotal: round($grandTotal, 2)
        );
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'discount_total' => $this->discountTotal,
            'surcharge_total' => $this->surchargeTotal,
            'grand_total' => $this->grandTotal,
        ];
    }
}
