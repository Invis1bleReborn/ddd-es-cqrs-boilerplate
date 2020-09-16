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

namespace IdentityAccess\Infrastructure\Identity\Specification;

use Common\Shared\Domain\Specification\AbstractSpecification;
use Doctrine\ORM\NonUniqueResultException;
use IdentityAccess\Domain\Identity\Exception\EmailAlreadyExistsException;
use IdentityAccess\Domain\Identity\Repository\CheckUserByEmailInterface;
use IdentityAccess\Domain\Identity\Specification\UniqueEmailSpecificationInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;

/**
 * Class UniqueEmailSpecification
 *
 * @package IdentityAccess\Infrastructure\Identity\Specification
 */
final class UniqueEmailSpecification extends AbstractSpecification implements UniqueEmailSpecificationInterface
{
    private CheckUserByEmailInterface $checkUserByEmail;

    public function __construct(CheckUserByEmailInterface $checkUserByEmail)
    {
        $this->checkUserByEmail = $checkUserByEmail;
    }

    public function isSatisfiedBy($value): bool
    {
        try {
            if ($this->checkUserByEmail->existsEmail($value)) {
                throw new EmailAlreadyExistsException($value);
            }
        } catch (NonUniqueResultException $e) {
            throw new EmailAlreadyExistsException($value);
        }

        return true;
    }

    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

}
