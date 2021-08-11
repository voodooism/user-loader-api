<?php
declare(strict_types=1);

namespace App\Controller;

use App\Exception\RESTException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class ErrorController
{
    private LoggerInterface $logger;

    private SerializerInterface $serializer;

    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    public function handleError(Throwable $exception): JsonResponse
    {
        $this->logException($exception);

        if (!$exception instanceof RESTException) {
            $exception = new RESTException($exception->getMessage(), 0, $exception);
        }

        return new JsonResponse(
            $this->serializer->serialize($exception, 'json'),
            $exception->getHttpCode(),
            [],
            true
        );
    }

    private function logException(Throwable $exception): void
    {
        $logMessage = sprintf(
            'Error occurs: %s, %s, %s',
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getTraceAsString()
        );

        $this->logger->error($logMessage);
    }
}
