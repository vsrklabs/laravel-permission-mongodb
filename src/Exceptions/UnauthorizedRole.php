<?php

namespace vsrklabs\Permission\Exceptions;

/**
 * Class UnauthorizedRole
 * @package Maklad\Permission\Exceptions
 */
class UnauthorizedRole extends UnauthorizedException
{
    /**
     * UnauthorizedPermission constructor.
     *
     * @param $statusCode
     * @param string|null $message
     * @param array $requiredRoles
     */
    public function __construct($statusCode, string $message = null, array $requiredRoles = [])
    {
        parent::__construct($statusCode, $message, $requiredRoles);
    }
}
