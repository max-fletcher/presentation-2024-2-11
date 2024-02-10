<?php

namespace App\Exceptions\api\v1;

use Exception;
use Illuminate\Http\Response;

class UnauthorizedException extends Exception
{
    protected $code = Response::HTTP_UNAUTHORIZED;

    // public function report(): void
    // {
    //     // ...
    // }

    // public function render(): JsonResponse
    // {
    // ...
    // }
}
