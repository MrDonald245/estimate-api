<?php

namespace App\Service;

use App\Adjustment\AdjustmentFactory;

class EstimateCalculator
{
    public function calculate(array $data): array
    {
        $totalWorks = 0;
        foreach ($data['works'] ?? [] as $work) {
            $totalWorks += $work['quantity'] * $work['unitPrice'];
        }

        $totalMaterials = 0;
        foreach ($data['materials'] ?? [] as $material) {
            $totalMaterials += $material['quantity'] * $material['unitPrice'];
        }

        $subtotal = $totalWorks + $totalMaterials;
        $total = $subtotal;

        foreach ($data['adjustments'] ?? [] as $adj) {
            $strategy = AdjustmentFactory::create($adj['type']);
            $total = $strategy->apply($total, $adj['value']);
        }

        return [
            'totalWorks' => $totalWorks,
            'totalMaterials' => $totalMaterials,
            'subtotal' => $subtotal,
            'adjustments' => $data['adjustments'] ?? [],
            'total' => $total
        ];
    }
}
