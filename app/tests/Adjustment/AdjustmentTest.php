<?php

namespace App\Tests\Adjustment;

use PHPUnit\Framework\TestCase;
use App\Adjustment\MarkupAdjustment;
use App\Adjustment\DiscountAdjustment;
use App\Adjustment\FixedCostAdjustment;

class AdjustmentTest extends TestCase
{
    public function testMarkup()
    {
        $strategy = new MarkupAdjustment();
        $result = $strategy->apply(100, 10);

        $this->assertEquals(110, $result);
    }

    public function testDiscount()
    {
        $strategy = new DiscountAdjustment();
        $result = $strategy->apply(100, 10);

        $this->assertEquals(90, $result);
    }

    public function testFixedCost()
    {
        $strategy = new FixedCostAdjustment();
        $result = $strategy->apply(100, 50);

        $this->assertEquals(150, $result);
    }
}
