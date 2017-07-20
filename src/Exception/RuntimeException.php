<?php

namespace Omni\Lead\Exception;

/**
 * Exception thrown if an error which can only be found on runtime occurs in the API client.
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
