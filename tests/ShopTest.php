<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class ShopTest extends WebTestCase
{
    private KernelBrowser $client;
    private Faker\Generator $faker;

    private const BEARER = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

    public function setUp(): void
    {
        $this->client = static::createClient([
            'Authorization' => 'Bearer '.self::BEARER,
        ]);
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
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.self::BEARER,
            ],
            json_encode($body),
        );

        $this->assertResponseIsSuccessful();
    }

    private function getRandomManager()
    {
        $this->client->request('GET', '/manager/list', [], [], [
            'Authorization' => 'Bearer '.self::BEARER,
        ]);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent());

        $maxRdm = count($responseData);
        $rdmIdx = rand(0, $maxRdm);

        return $responseData[$rdmIdx];
    }
}
