<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $product = new Product('Product_' . $i, 100 + $i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
