<?php

use RedBeanPHP\Facade as R;

$dsn = sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_NAME']);
R::setup($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']); //for both mysql or mariaDB
