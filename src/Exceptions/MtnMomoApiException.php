<?php

namespace AlvinCoded\MtnMomoAi\Exceptions;

use Exception;

/**
 * MTN MOMO API Exception Handler
 *
 * This exception class handles specific errors that occur during
 * interactions with the MTN Mobile Money API. It extends the base
 * PHP Exception class and adds functionality to store and retrieve
 * the API response body.
 *
 * @package AlvinCoded\MtnMomoAi\Exceptions
 * @author Alvin Panford <panfordalvin@gmail.com>
 * @since 1.0.0
 */
class MtnMomoApiException extends Exception
{
    /**
     * The raw response body from the API request
     *
     * @var mixed|null
     */
    protected $responseBody;

    /**
     * Create a new MTN MOMO API Exception instance
     *
     * @param string $message The exception message
     * @param int $code The exception code (defaults to 0)
     * @param mixed|null $responseBody The raw API response body
     * 
     * @return void
     */
    public function __construct($message, $code = 0, $responseBody = null)
    {
        parent::__construct($message, $code);
        $this->responseBody = $responseBody;
    }

    /**
     * Get the raw response body from the API request
     *
     * This method returns the complete response body received from
     * the MTN MOMO API during the failed request. This can be useful
     * for debugging purposes or for logging detailed error information.
     *
     * @return mixed|null The response body or null if not available
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
