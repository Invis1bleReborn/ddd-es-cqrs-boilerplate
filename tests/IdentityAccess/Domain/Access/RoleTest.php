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

namespace IdentityAccess\Domain\Access;

use IdentityAccess\Domain\Access\ValueObject\Role;
use PHPUnit\Framework\TestCase;

/**
 * Class RoleTest
 *
 * @package IdentityAccess\Domain\Access
 */
class RoleTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidRoleProvider
     *
     * @param string $role
     */
    public function itThrowsExceptionOnInvalidRole(string $role): void
    {
        $this->expectException(\UnexpectedValueException::class);

        new Role($role);
    }

    public function invalidRoleProvider(): array
    {
        return [
            [ 'ROLE_INVALID_ROLE' ],
            [ '' ],
        ];
    }
}
