<?php

namespace App\Adjustment;

use InvalidArgumentException;

class AdjustmentFactory
{
    public static function create(string $type): AdjustmentInterface
    {
        return match($type) {
            'markup' => new MarkupAdjustment(),
            'discount' => new DiscountAdjustment(),
            'fixed_cost' => new FixedCostAdjustment(),
            default => throw new InvalidArgumentException("Unknown adjustment type {$type}"),
        };
    }
}
