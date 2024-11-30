<?php

use PH7\PhpHttpResponseHeader\Http;
use PH7\JustHttp\StatusCode;

function notFoundResponse(): void
{
    Http::setHeadersByCode(StatusCode::NOT_FOUND);
    echo json_encode([
        'error' => 'request not found'
    ]);
}

notFoundResponse();