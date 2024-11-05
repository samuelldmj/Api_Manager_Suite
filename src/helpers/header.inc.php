<?php

namespace Src\Helpers;

use PH7\PhpHttpResponseHeader\Http;
use Src\AllowCors;

Http::setContentType('application/json');

(new AllowCors)->init();
header('Content-Type: application/json');
