<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsHttpException extends HttpException
{
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;

        parent::__construct(Response::HTTP_BAD_REQUEST, 'Validation error');
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
