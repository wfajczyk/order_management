<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Entity\Order;
use App\Repository\ProductRepository;
use App\Service\OrderCreateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: "order/recreate/{order}",
    name: "order_recreate",
    methods: ["POST"]
)]
class RecreateOrderAction
{
    public function __construct(private readonly OrderCreateService $createService)
    {
    }

    public function __invoke(Order $order): JsonResponse
    {
        $newOrder = $this->createService->reCreate($order);

        return new JsonResponse($newOrder);
    }
}
