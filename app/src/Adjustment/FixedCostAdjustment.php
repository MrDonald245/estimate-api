<?php
// src/Adjustment/FixedCostAdjustment.php
namespace App\Adjustment;

class FixedCostAdjustment implements AdjustmentInterface
{
    public function apply(float $amount, float $value): float
    {
        return $amount + $value;
    }
}
