<?php

namespace App\Adjustment;

interface AdjustmentInterface
{
    public function apply(float $amount, float $value): float;
}
