<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class ShopTest extends WebTestCase
{
    private KernelBrowser $client;
    private Faker\Generator $faker;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->faker = Faker\Factory::create();
    }

    public function testCreate(): void
    {
        $body = [
            'name' => $this->faker->name(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'postalAddress' => $this->faker->address(),
            'manager' => $this->getRandomManager()->id,
        ];

        $this->client->request(
            'POST',
            '/shop/create',
            [],
            [],
            ['Content-Type' => 'application/json'],
            json_encode($body),
        );

        $this->assertResponseIsSuccessful();
    }

    private function getRandomManager()
    {
        $this->client->request('GET', '/manager/list');
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent());

        $maxRdm = count($responseData);
        $rdmIdx = rand(0, $maxRdm);

        return $responseData[$rdmIdx];
    }
}
