<?php

namespace Stereoqweex\TestDromPackage\Exception;

use Exception;

class HttpClientException extends Exception
{
    public function __construct(string $message = '', int $code = 500)
    {
        parent::__construct("Возникла ошибка при выполнении запроса: $message", $code);
    }
}