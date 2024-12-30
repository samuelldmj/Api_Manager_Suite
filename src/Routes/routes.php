<?php

use Src\Exceptions\InvalidCredentialException;

require_once __DIR__  .'/'. '../Helpers/misc.inc.php';

$resource = $_REQUEST['resource'] ?? null;
// $action = $_REQUEST['action'] ?? null;

// if (!$action) {
//     // Action is missing, handle the error here
//     echo "From Routes" . PHP_EOL;
//     require_once 'route-not-found.php';  
//     exit;  
// }
try {
    switch ($resource) {
        case 'user':
            require_once 'UserActionRoute.php';
            break;
            
        case 'foodItem':
            require_once 'FoodItemActionRoute.php';
            break;
    
        default:
            require_once 'route-not-found.php';
            break;
    }
    //throw the error from the user file, then catch it here, the extracted only the message in the response array.
}catch( InvalidCredentialException $e){
    response([
        'message' => $e->getMessage()
    ]);
}

