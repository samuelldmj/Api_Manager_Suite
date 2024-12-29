<?php
namespace Src\Exceptions;

class EmailExistException extends \RuntimeException {
    protected $message = "This email address already exists";
}
