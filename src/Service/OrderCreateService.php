<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Repository\OrderRepository;

class OrderCreateService
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    /**
     * @param array<Product> $products
     */
    public function create(array $products): Order
    {
        $order = new Order();

        foreach ($products as $product) {
            $orderProduct = new OrderProduct($product, 1, $product->getPrice());
            $order->add($orderProduct);
        }

        $this->orderRepository->save($order);

        return  $order;
    }
}
