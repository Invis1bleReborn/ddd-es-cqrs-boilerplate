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
 * Class AccessAttribute
 *
 * @package IdentityAccess\Ui\Access
 */
final class AccessAttribute
{
    public string $attribute;

    public ?string $field;

    public function __construct(string $attribute, ?string $field = null)
    {
        $this->attribute = $attribute;
        $this->field = $field;
    }

}
