<?php

declare(strict_types=1);

namespace App\Controller\HttpKernel;

use App\Exception\ViolationsHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOInputResolver implements ValueResolverInterface
{
    public const JSON = 'json';

    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator)
    {
    }

    /**
     * @return iterable<object|null>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $contentTypeFormat = $request->getContentTypeFormat();

        if ($contentTypeFormat !== self::JSON){
            throw new UnsupportedMediaTypeHttpException('The "Content-Type" unknown.');
        }

        if (!$request->getContent()) {
            throw new BadRequestHttpException('Missing request body');
        }

        $type = $argument->getType();
        if ($argument->isVariadic()) {
            $type .= '[]';
        }

        $dto = $this->serializer->deserialize(
            $request->getContent(),
            $type,
            $contentTypeFormat,
            [
                'expectUserMessage' => true,
                'allowExtraAttributes' => true,
            ],
        );

        $violationList = $this->validator->validate($dto);
        if ($violationList->count()) {
            throw new ViolationsHttpException($violationList);
        }

        if (!$argument->isVariadic()) {
            $dto = [$dto];
        }

        foreach ($dto as $item) {
            yield $item;
        }
    }
}
