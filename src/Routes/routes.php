<?php

use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;

$resource = $_REQUEST['resource'] ?? null;

switch ($resource){
    case 'user':
        return require_once 'UserActionRoute.php';

    default:
    require_once "route-not-found.php" ;
}