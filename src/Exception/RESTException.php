<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class RESTException extends Exception
{
    protected int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
