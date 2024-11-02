<?php

$resource = $_GET['resource'] ?? null;

switch ($resource){
    case 'user':
        return require_once 'UserAction.php';

    default:
        
}