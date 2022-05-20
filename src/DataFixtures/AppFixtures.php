<?php

namespace App\DataFixtures;

use App\Entity\Manager;
use App\Entity\Product;
use App\Entity\ProductAvailability;
use App\Entity\Shop;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;
    private Faker\Generator $faker;

    /** @var Manager[] */
    private array $managers;

    /** @var Shop[] */
    private array $shops;

    /** Set max items */
    const MAX_MANAGERS = 10;
    const MAX_SHOPS = 30;
    const MAX_PRODUCTS = 500;
    const MAX_AVAILABILITY = 30;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->createManagers();
        $this->createShops();
        $this->createProducts();

        $manager->flush();
    }

    private function createManagers()
    {
        for ($i = 0; $i < self::MAX_MANAGERS; $i++) {
            $manager = new Manager();
            $manager->setFirstName($this->faker->firstName());
            $manager->setLastName($this->faker->lastName());

            $this->manager->persist($manager);
            $this->managers[] = $manager;
        }

        $this->manager->flush();
    }

    private function createShops()
    {
        for ($i = 0; $i < self::MAX_SHOPS; $i++) {
            $shop = new Shop();
            $shop->setName($this->faker->name());
            $shop->setLat($this->faker->latitude());
            $shop->setLng($this->faker->longitude());
            $shop->setPostalAddress($this->faker->address());
            $shop->setManager($this->getRandomElement($this->managers));

            $this->manager->persist($shop);

            $this->shops[] = $shop;
        }

        $this->manager->flush();
    }

    private function createProducts()
    {
        for ($i = 0; $i < self::MAX_PRODUCTS; $i++) {
            $product = new Product();
            $product->setName($this->faker->name());
            $product->setPictureUrl($this->faker->imageUrl());

            $this->manager->persist($product);

            $this->assignStock($product);
        }

        $this->manager->flush();
    }

    public function getRandomElement(array $elements, $exclude = [])
    {
        $key = array_rand($elements);
        $excludeIds = array_map(function ($elem) {
            return $elem->getId();
        }, $exclude);

        if (in_array($elements[$key], $excludeIds)) {
            return $this->getRandomElement($elements, $exclude);
        }

        return $elements[$key];
    }

    private function assignStock(Product $product)
    {
        $shopsToAssign = rand(0, count($this->shops));
        $shopHistory = [];

        for ($i = 0; $i <= $shopsToAssign; $i++) {
            $availability = new ProductAvailability();
            $availability->setProduct($product);
            $availability->setAvailability(rand(0, self::MAX_AVAILABILITY));
            $randomShop = $this->getRandomElement($this->shops, $shopHistory);
            $availability->setShop($randomShop);

            $this->manager->persist($availability);
        }
    }
}
