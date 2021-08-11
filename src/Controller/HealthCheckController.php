<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController
{
    /**
     * @Route("/", name="app_healthcheck", methods={"GET"})
     */
    public function healthCheck(): JsonResponse
    {
        return new JsonResponse(['status' => "I am alive!"]);
    }
}
