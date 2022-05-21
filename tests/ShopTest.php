<?php

namespace App\Tests;

use stdClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;
use Symfony\Component\Dotenv\Dotenv;

class ShopTest extends WebTestCase
{
    private KernelBrowser $client;
    private Faker\Generator $faker;
    private string $bearer;

    public function setUp(): void
    {
        $this->fetchBearer();

        // Create http client
        $this->client = static::createClient([], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$this->bearer,
            'HTTP_CONTENT_TYPE' => 'application/json',
        ]);

        // Init faker
        $this->faker = Faker\Factory::create();
    }

    public function testCreateShop(): void
    {
        $body = [
            'name' => $this->faker->name(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'postalAddress' => $this->faker->address(),
            'manager' => $this->getRandomManager()->id,
        ];

        $this->client->request('POST', '/shop/create', [], [], [], json_encode($body));

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testListShop()
    {
        $this->client->request('GET', '/shop/list');

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testListShopWithOptions()
    {
        $options = [
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'radius' => rand(10000, 1000000),
        ];

        $this->client->request('GET', '/product/list?'.http_build_query($options));

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testListProduct()
    {
        $this->client->request('GET', '/product/list');

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testListProductWithOptions()
    {
        $options = [
            'availabilityMin' => rand(0, 20),
            'availabilityMax' => rand(21, 50),
            'shops' => $this->getRandomShop()->id,
        ];

        $this->client->request('GET', '/product/list?'.http_build_query($options));

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testSetAvailability()
    {
        $inputs = [];

        for ($i = 0; $i < rand(2, 5); $i++) {
            $input = new stdClass();
            $input->shop = $this->getRandomShop()->id;
            $input->product = $this->getRandomProduct()->id;
            $input->availability = rand(1, 123);

            $inputs = $input;
        }

        $this->client->request('POST', '/product/availability', [], [], [], json_encode($inputs));

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    private function getRandomProduct()
    {
        return $this->getRandomPaginatedItem('/product/list');
    }

    private function getRandomShop()
    {
        return $this->getRandomPaginatedItem('/shop/list');
    }

    private function getRandomManager()
    {
        return $this->getRandomPaginatedItem('/manager/list');
    }

    private function getRandomPaginatedItem($endpoint)
    {
        $this->client->request('GET', $endpoint);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent());

        $maxRdm = count($responseData->items) - 1;
        $rdmIdx = rand(0, $maxRdm);

        return $responseData->items[$rdmIdx];
    }

    /**
     * Fetch bearer from dotenv
     *
     * @return void
     */
    private function fetchBearer()
    {
        $dotenv = new Dotenv();
        $envPath = realpath(__DIR__.'/../.env');
        $dotenv->load($envPath);

        $this->bearer = $_ENV['BEARER_API_TOKEN'];
    }
}
