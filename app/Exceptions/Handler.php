<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponce;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
class Handler extends ExceptionHandler
{
    use ApiResponce;

    protected function invalidJson($request, ValidationException $exception)
    {
        return $this->ErrorMessage(
            $exception->errors(),
            "Wrong in input data",
            $exception->status
        );
    }
}
