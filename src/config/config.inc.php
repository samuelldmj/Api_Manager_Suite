<?php
namespace Src\Config;

use Dotenv\Dotenv;

enum Environment :string
{
    case DEVELOPMENT = 'development';
    case PRODUCTION = 'production';

    public function environmentName(){
         $environment = match($this){
           self::PRODUCTION  => 'production' ,
           self::DEVELOPMENT => 'development'
        };

        return $environment;
    }
}


$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

