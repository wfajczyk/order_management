<?php

declare(strict_types=1);

namespace App\Controller\Order;

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
    public function __construct(private readonly OrderCreateService $createService, private ProductRepository $productRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        $product = $this->productRepository->find(1);

        $order = $this->createService->create([$product]);

        return new JsonResponse($order->getId());
    }
}
