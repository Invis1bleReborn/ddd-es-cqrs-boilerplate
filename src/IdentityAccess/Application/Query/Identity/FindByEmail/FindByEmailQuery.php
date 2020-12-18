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

namespace IdentityAccess\Application\Query\Identity\FindByEmail;

use Assert\AssertionFailedException;
use Common\Shared\Application\Query\QueryInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;

/**
 * Class FindByEmailQuery.
 */
class FindByEmailQuery implements QueryInterface
{
    public Email $email;

    /**
     * FindByEmailQuery constructor.
     *
     * @param string $email
     *
     * @throws AssertionFailedException
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
