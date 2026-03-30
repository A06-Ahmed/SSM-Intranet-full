<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="SMM Intranet API",
 *     version="1.0.0",
 *     description="API documentation for SMM Intranet backend."
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Local API server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class OpenApi
{
}
