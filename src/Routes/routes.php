<?php

use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Src\Exceptions\InvalidCredentialException;
use Src\Exceptions\InvalidValidationException;

require_once __DIR__  .'/'. '../Helpers/misc.inc.php';

$resource = $_REQUEST['resource'] ?? null;

try {
    switch ($resource) {
        case 'user':
            require_once 'UserActionRoute.php';
            break;
            
        case 'item':
            require_once 'ItemActionRoute.php';
            break;
    
        default:
            require_once 'route-not-found.php';
            break;
    }
    //throw the error from the user file, then catch it here, the extracted only the message in the response array.
}catch( InvalidCredentialException $e){
    response([
        'error' => [
        'message' => $e->getMessage()
   ]
 ]);
}catch (InvalidValidationException $e) {
    // Handle errors and set HTTP status to BAD_REQUEST
    Http::setHeadersByCode(StatusCode::BAD_REQUEST);
    response([
        'error' => [
            'message' => $e->getMessage(),
        ]
    ]);
}

