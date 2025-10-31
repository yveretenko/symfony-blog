<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends RuntimeException
{
    public function __construct(
        private readonly ConstraintViolationListInterface $errors
    ) {
        parent::__construct('Validation failed.');
    }

    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
