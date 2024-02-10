<?php

namespace App\Exceptions\api\v1;

use Exception;
use Illuminate\Http\Response;

class ForbiddenException extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    // public function report(): void
    // {
    //     // ...
    // }

    // public function render(): JsonResponse
    // {
    // ...
    // }
}
