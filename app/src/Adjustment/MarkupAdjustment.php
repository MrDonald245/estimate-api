<?php

namespace App\Adjustment;

class MarkupAdjustment implements AdjustmentInterface
{
    public function apply(float $amount, float $value): float
    {
        return $amount + ($amount * $value / 100);
    }
}
