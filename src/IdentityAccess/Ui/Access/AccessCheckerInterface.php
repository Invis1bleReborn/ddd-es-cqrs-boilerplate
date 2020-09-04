<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access;

/**
 * Interface AccessCheckerInterface
 *
 * @package IdentityAccess\Ui\Access
 */
interface AccessCheckerInterface
{
    /**
     * @param string        $attribute
     * @param object|string $subject
     * @param string|null   $field
     *
     * @return bool
     */
    public function isGranted(
        string $attribute,
        $subject,
        string $field = null
    ): bool;

}
