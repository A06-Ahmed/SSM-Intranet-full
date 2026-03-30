<?php

namespace App\Http\Controllers;

use App\Support\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;

    protected function success(string $message, mixed $data = null, int $status = 200)
    {
        return ApiResponse::success($message, $data, $status);
    }

    protected function error(string $message, mixed $errors = null, int $status = 400)
    {
        return ApiResponse::error($message, $errors, $status);
    }
}
