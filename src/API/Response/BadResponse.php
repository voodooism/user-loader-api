<?php
declare(strict_types=1);

namespace App\API\Response;

class BadResponse extends AbstractResponse
{
    private string $errorMessage;

    public function __construct(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}
