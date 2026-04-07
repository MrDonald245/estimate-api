<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EstimateControllerTest extends WebTestCase
{
    public function testEstimateEndpoint()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/estimate',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'works' => [
                    ['id' => 1, 'name' => 'Монтаж', 'quantity' => 10, 'unitPrice' => 100]
                ],
                'materials' => [
                    ['id' => 1, 'name' => 'Кабель', 'quantity' => 20, 'unitPrice' => 10]
                ],
                'adjustments' => [
                    ['type' => 'markup', 'value' => 10]
                ]
            ])
        );

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        // Считаем total правильно
        // works: 10*100 = 1000
        // materials: 20*10 = 200
        // subtotal = 1200
        // markup 10% => 1200 * 1.1 = 1320
        $this->assertEquals(1320, $data['total']);
    }
}
