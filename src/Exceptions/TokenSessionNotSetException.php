<?php

namespace Src\Exceptions;

use RuntimeException;

class TokenSessionNotSetException extends RuntimeException {

    protected $message = "Token session cannot be set for this user";
}