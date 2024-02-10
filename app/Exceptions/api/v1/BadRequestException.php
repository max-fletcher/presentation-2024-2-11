<?php

namespace App\Exceptions\api\v1;

use Exception;
use Illuminate\Http\Response;

class BadRequestException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    // public function report(): void
    // {
    //     // ...
    // }

    // public function render(): JsonResponse
    // {
    // ...
    // }
}
