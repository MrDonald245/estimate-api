<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EstimateRequest
{
    #[Assert\Type('array')]
    #[Assert\All([
        new Assert\Collection([
            'id' => [new Assert\NotBlank(), new Assert\Type('integer')],
            'name' => [new Assert\NotBlank(), new Assert\Type('string')],
            'quantity' => [new Assert\NotBlank(), new Assert\Type('numeric'), new Assert\Positive()],
            'unitPrice' => [new Assert\NotBlank(), new Assert\Type('numeric'), new Assert\Positive()],
        ])
    ])]
    public array $works = [];

    #[Assert\Type('array')]
    #[Assert\All([
        new Assert\Collection([
            'id' => [new Assert\NotBlank(), new Assert\Type('integer')],
            'name' => [new Assert\NotBlank(), new Assert\Type('string')],
            'quantity' => [new Assert\NotBlank(), new Assert\Type('numeric'), new Assert\Positive()],
            'unitPrice' => [new Assert\NotBlank(), new Assert\Type('numeric'), new Assert\Positive()],
        ])
    ])]
    public array $materials = [];

    #[Assert\Type('array')]
    #[Assert\All([
        new Assert\Collection([
            'type' => [
                new Assert\NotBlank(),
                new Assert\Choice(choices: ['markup', 'discount', 'fixed_cost']) // <- исправлено
            ],
            'value' => [
                new Assert\NotBlank(),
                new Assert\Type('numeric')
            ],
        ])
    ])]
    public array $adjustments = [];
}
