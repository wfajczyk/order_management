<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\DTO\DTOOrderProduct;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;

class OrderCreateService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly ProductRepository $productRepository
    ) {
    }

    /**
     * @param array<DTOOrderProduct> $DTOOrder
     */
    public function create(array $DTOOrder): Order
    {
        $order = new Order();
        foreach ($DTOOrder as $dtoProduct) {
            $product = $this->productRepository->find($dtoProduct->id);
            if ($product === null) {
                throw new \RuntimeException('Product not found');
            }
            $orderProduct = new OrderProduct($product, $dtoProduct->quantity, $product->getPrice());
            $order->add($orderProduct);
        }

        $this->orderRepository->save($order);

        return $order;
    }

    public function reCreate(Order $order): Order
    {
        $newOrder = new Order();
        foreach ($order->getProducts() as $product) {
            $orderProduct = new OrderProduct($product->getProduct(), 1, $product->getPrice());
            $newOrder->add($orderProduct);
        }

        $this->orderRepository->save($newOrder);

        return $newOrder;
    }
}
