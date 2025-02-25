<?php
namespace Src\Exceptions;

use RuntimeException;

class InvalidCredentialException extends RuntimeException {

    protected $message = 'Invalid email or password.';
}