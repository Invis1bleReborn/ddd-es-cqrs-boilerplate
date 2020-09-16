<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
