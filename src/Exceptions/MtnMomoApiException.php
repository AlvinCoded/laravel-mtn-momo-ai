<?php

namespace AlvinCoded\MtnMomoAi\Exceptions;

use Exception;

class MtnMomoApiException extends Exception
{
    protected $responseBody;

    public function __construct($message, $code = 0, $responseBody = null)
    {
        parent::__construct($message, $code);
        $this->responseBody = $responseBody;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
