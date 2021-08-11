<?php
declare(strict_types=1);

namespace App\Exception;

use ArrayObject;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;

class RESTExceptionHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => RESTException::class,
                'method' => 'serializeToJson',
            ]
        ];
    }

    /**

     * @return array|ArrayObject
     */
    public function serializeToJson(
        JsonSerializationVisitor $visitor,
        RESTException $exception,
        array $type
    ) {
        $data = $this->convertThrowableToArray($exception);

        return $visitor->visitArray($data, $type);
    }

    private function convertThrowableToArray(RESTException $exception): array
    {
        $data = [];

        if (!empty($exception->getMessage())) {
            $data['message'] = $exception->getMessage();
        }

        if ($exception instanceof ValidationException) {
            $data['validation_errors'] = $exception->getValidationErrors();
        }

        return $data;
    }
}
