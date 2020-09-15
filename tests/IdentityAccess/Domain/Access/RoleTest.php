<?php

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
