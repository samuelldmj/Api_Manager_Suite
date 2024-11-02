<?php
namespace Src\Exceptions;
use Exception;
class InvalidValidationException extends Exception
{
   protected $message =  'Invalid Data';
}