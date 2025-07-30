<?php

namespace App\Exception;

use Exception;

class BadRequestException extends Exception implements ApplicationException
{
    protected $code = self::BAD_REQUEST;
}
