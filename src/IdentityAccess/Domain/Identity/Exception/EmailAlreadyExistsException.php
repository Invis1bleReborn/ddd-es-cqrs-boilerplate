<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\Exception;

use IdentityAccess\Domain\Identity\ValueObject\Email;

/**
 * Class EmailAlreadyExistsException
 *
 * @package IdentityAccess\Domain\Identity\Exception
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
