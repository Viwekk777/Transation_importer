<?php

namespace App\Exceptions;

class PDOException extends \Exception
{
    protected $message = '500 Internal Database Error';

}