<?php
namespace Src\Routes;
class CustomHttp
{

    public const POST_METHOD = 'POST';
    public const GET_METHOD = 'GET';
    public const PUT_METHOD = 'PUT';
    public const DELETE_METHOD = 'DELETE';




    public static function httpMethodValidator(string $httpMethod)
    {
        return strtolower($_SERVER['REQUEST_METHOD']) === strtolower($httpMethod);
    }
}