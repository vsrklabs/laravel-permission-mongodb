<?php

namespace vsrklabs\Permission\Exceptions;

/**
 * Class UnauthorizedPermission
 * @package Maklad\Permission\Exceptions
 */
class UnauthorizedPermission extends UnauthorizedException
{
    /**
     * UnauthorizedPermission constructor.
     *
     * @param $statusCode
     * @param string|null $message
     * @param array $requiredPermissions
     */
    public function __construct($statusCode, string $message = null, array $requiredPermissions = [])
    {
        parent::__construct($statusCode, $message, [], $requiredPermissions);
    }
}
