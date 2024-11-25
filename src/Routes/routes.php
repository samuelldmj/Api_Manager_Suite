<?php

use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;

$resource = $_REQUEST['resource'] ?? null;

switch ($resource){
    case 'user':
        return require_once 'UserActionRoute.php';

    default:
    Http::setHeadersByCode(StatusCode::NOT_FOUND);
    echo $response = json_encode([
        'error' => 'request not found'
    ]
    );   
}