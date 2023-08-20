<?php


declare(strict_types=1);

namespace App\Controller\ParamConverter;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderParamConverter implements ParamConverterInterface
{

    public function __construct( private readonly OrderRepository $orderRepository)
    {
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $parameter = $request->get($configuration->getName());
        if(!is_numeric($parameter)){
            throw new BadRequestHttpException();
        }
        $order = $this->orderRepository->find($parameter);

        if($order === null){
            throw new NotFoundHttpException();
        }

        $request->attributes->set($configuration->getName(), $order);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return Order::class === $configuration->getClass();
    }
}
