<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\EstimateCalculator;

class EstimateCalculatorTest extends TestCase
{
    public function testCalculation()
    {
        $calculator = new EstimateCalculator();

        $data = [
            'works' => [
                ['quantity' => 10, 'unitPrice' => 100]
            ],
            'materials' => [
                ['quantity' => 5, 'unitPrice' => 20]
            ],
            'adjustments' => [
                ['type' => 'markup', 'value' => 10]
            ]
        ];

        $result = $calculator->calculate($data);

        $this->assertEquals(1000, $result['totalWorks']);
        $this->assertEquals(100, $result['totalMaterials']);
        $this->assertEquals(1100, $result['subtotal']);
        $this->assertEquals(1210, $result['total']);
    }
}
