<?php

$resource = $_REQUEST['resource'] ?? null;
$action = $_REQUEST['action'] ?? null;

if (!$action) {
    // Action is missing, handle the error here
    echo "From Routes" . PHP_EOL;
    require_once 'route-not-found.php';  
    exit;  
}

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

