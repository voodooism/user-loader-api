<?php
declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class ValidationException extends RESTException
{
    protected int $httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;

    private array $validationErrors = [];

    public function setValidationErrors(array $validationErrors): void
    {
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
