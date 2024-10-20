<?php
//__dir__ used to obtain current code directory, that is the directory from which the code is being used.
require __DIR__ . '/vendor/autoload.php';
//require once would first look internally through the directory to check  if the file has been added. could be slower, but the impact is little.
require __DIR__ .  'src/routes.php';

require __DIR__ . '/src/helpers/header.inc.php';