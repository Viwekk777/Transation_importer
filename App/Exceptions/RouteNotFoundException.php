<?php

declare(strict_types = 1);

namespace App\Exceptions;

class RouteNotFoundException extends \Exception
{
    public function __construct($message = 'Not Found')
    {
        parent::__construct($message, 404);
    }
}