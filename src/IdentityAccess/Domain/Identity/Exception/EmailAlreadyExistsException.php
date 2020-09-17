<?php

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\Exception;

use IdentityAccess\Domain\Identity\ValueObject\Email;

/**
 * Class EmailAlreadyExistsException
 */
class EmailAlreadyExistsException extends \InvalidArgumentException
{
    public function __construct(Email $email)
    {
        parent::__construct(sprintf(
            'Email "%s" already registered.',
            $email->toString())
        );
    }
}
