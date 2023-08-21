<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Controller\DTO\DTOOrder;
use App\Controller\DTO\DTOOrderProduct;
use App\Repository\ProductRepository;
use App\Service\OrderCreateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: "order/create",
    name: "order_create",
    methods: ["POST"]
)]
class CreateOrderAction
{
    public function __construct(private readonly OrderCreateService $createService)
    {
    }

    public function __invoke(DTOOrderProduct... $DTOOrder): JsonResponse
    {

        $order = $this->createService->create($DTOOrder);

        return new JsonResponse($order->getId());
    }
}
