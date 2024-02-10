<?php

namespace App\Exceptions\api\v1;

use Exception;
use Illuminate\Http\Response;

class NotFoundException extends Exception
{
    protected $code = Response::HTTP_NOT_FOUND;

    // public function report(): void
    // {
    //     // ...
    // }

    // public function render(): JsonResponse
    // {
    // ...
    // }
}
