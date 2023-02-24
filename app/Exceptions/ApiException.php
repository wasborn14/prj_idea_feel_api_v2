<?php

namespace App\Exceptions;

use Throwable;

class ApiException extends \Exception
{
    protected $tip;

    public function __construct(string $message = "", int $code = 0, string $tip = "", Throwable $previous = null)
    {
        $this->tip = $tip;
        parent::__construct($message, $code, $previous);
    }

    public function getTip(): string
    {
        return $this->tip;
    }
}
