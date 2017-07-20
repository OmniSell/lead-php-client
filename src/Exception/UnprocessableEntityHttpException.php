<?php

namespace Omni\Lead\Exception;

/**
 * Exception thrown when it is the request is unprocessable (422).
 */
class UnprocessableEntityHttpException extends ClientErrorHttpException
{
    /**
     * Returns the errors of the response if there are any
     *
     * @return array
     */
    public function getResponseErrors()
    {
        $responseBody = $this->response->getBody();

        $responseBody->rewind();
        $decodedBody = json_decode($responseBody->getContents(), true);
        $responseBody->rewind();

        return isset($decodedBody['errors']) ? $decodedBody['errors'] : [];
    }
}
