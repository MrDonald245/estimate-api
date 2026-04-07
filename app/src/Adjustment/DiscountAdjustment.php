<?php
// src/Adjustment/DiscountAdjustment.php
namespace App\Adjustment;

class DiscountAdjustment implements AdjustmentInterface
{
    public function apply(float $amount, float $value): float
    {
        return $amount - ($amount * $value / 100);
    }
}
