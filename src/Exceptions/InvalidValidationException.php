<?php
namespace Src\Exceptions;
use InvalidArgumentException;

class InvalidValidationException extends InvalidArgumentException
{
   protected $message =  'Invalid Data';
}