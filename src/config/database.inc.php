<?php

use RedBeanPHP\Facade as R;
use Src\Config\Environment;

$dsn = sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_NAME']);
R::setup($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']); //for both mysql or mariaDB

$currentEnvironment = Environment::tryFrom($_ENV['ENVIRONMENT']);

//testing what environment variable is passed.
// var_dump($currentEnvironment->value);

//if this is on production, our database structure will be on frozen state.
//added null safe to handle null value. tryform() method will return null if the environment is not correctly specified.
if($currentEnvironment?->environmentName() === Environment::PRODUCTION->value){                           
    echo "RedBean Frozen";
    R::freeze(true);
}
