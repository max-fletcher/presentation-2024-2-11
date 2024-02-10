<?php

namespace App\Exceptions\api\v1;

use Exception;
use Illuminate\Http\Response;

class ValidationException extends Exception
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    // public function report(): void
    // {
    //     // ...
    // }

    // public function render(): JsonResponse
    // {
    // ...
    // }
}
