<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: "order/remove/{order}",
    name: "order_remove",
    methods: ["POST"]
)]
class RemoveOrderAction
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    public function __invoke(Order $order): Response
    {
        $this->orderRepository->remove($order);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

}
