<?php

namespace App\Exception;

use Exception;

class ForbiddenException extends Exception implements ApplicationException
{
    protected $code = self::FORBIDDEN;
}
