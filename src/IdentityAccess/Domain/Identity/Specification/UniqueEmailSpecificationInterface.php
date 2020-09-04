<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Identity\Specification;

use IdentityAccess\Domain\Identity\ValueObject\Email;

/**
 * Interface UniqueEmailSpecificationInterface
 *
 * @package IdentityAccess\Domain\Identity\Specification
 */
interface UniqueEmailSpecificationInterface
{
    public function isUnique(Email $email): bool;

}
