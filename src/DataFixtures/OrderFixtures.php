<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $order = new Order();

            /** @var Product $product */
            $product = $manager->getRepository(Product::class)->findOneBy(['id' => $i + 1]);
            $orderProduct = new OrderProduct($product, $i, $product->getPrice());
            $order->add($orderProduct);

            $manager->persist($order);
        }

        $manager->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}
