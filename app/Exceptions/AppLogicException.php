<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class AppLogicException extends Exception
{
    protected $code = 500;
}
