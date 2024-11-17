<?php

//__dir__ can be used to obtain current code directory, that is the directory from which the code is being used.
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/Helpers/header.inc.php';
require __DIR__ . '/src/config/config.inc.php';
require __DIR__ . "/src/config/database.inc.php";
//require once would first look internally through the directory to check  if the file has been added. could be slower, but the impact is little.
require __DIR__ .  '/src/Routes/routes.php';
