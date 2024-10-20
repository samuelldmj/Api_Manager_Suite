<?php

namespace Src\Helpers;
use Src\AllowCors;

(new AllowCors)->init();
header('Content-Type: application/json');
