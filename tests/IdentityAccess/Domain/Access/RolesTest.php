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
use IdentityAccess\Domain\Access\ValueObject\Roles;
use PHPUnit\Framework\TestCase;

/**
 * Class RolesTest
 *
 * @package IdentityAccess\Domain\Access
 */
class RolesTest extends TestCase
{
    /**
     * @test
     */
    public function transformingSelfToArrayYieldsTheSameRoles(): void
    {
        $expected = [
            'ROLE_SUPER_ADMIN',
            'ROLE_USER',
        ];

        $this->assertSame($expected, Roles::fromArray($expected)->toArray());
    }

    /**
     * @test
     * @dataProvider equalsRolesProvider
     *
     * @param string[] $roles1
     * @param string[] $roles2
     */
    public function itEqualsToTheSameRoles(array $roles1, array $roles2)
    {
        $this->assertTrue(Roles::fromArray($roles1)->equals(Roles::fromArray($roles2)));
    }

    public function equalsRolesProvider(): array
    {
        return [
            [
                // no roles
                [],
                [],
            ],
            [
                ['ROLE_USER'],
                ['ROLE_USER'],
            ],
            [
                ['ROLE_SUPER_ADMIN', 'ROLE_USER'],
                ['ROLE_USER', 'ROLE_SUPER_ADMIN'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider notEqualsRolesProvider
     *
     * @param string[] $roles1
     * @param string[] $roles2
     */
    public function itNotEqualsToTheAnotherRoles(array $roles1, array $roles2)
    {
        $this->assertFalse(Roles::fromArray($roles1)->equals(Roles::fromArray($roles2)));
    }

    public function notEqualsRolesProvider(): array
    {
        return [
            [
                // no roles
                [],
                ['ROLE_USER'],
            ],
            [
                [],
                // no roles
                ['ROLE_USER'],
            ],
            [
                ['ROLE_USER'],
                ['ROLE_SUPER_ADMIN'],
            ],
            [
                ['ROLE_SUPER_ADMIN'],
                ['ROLE_USER'],
            ],
            [
                ['ROLE_SUPER_ADMIN', 'ROLE_USER'],
                ['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider containsRoleDataProvider
     *
     * @param string $role
     */
    public function itContainsPreviouslyAddedRole(string $role): void
    {
        $roles = Roles::fromArray([$role]);

        $this->assertTrue($roles->contains(new Role($role)));
    }

    public function containsRoleDataProvider(): array
    {
        return [
            [ 'ROLE_SUPER_ADMIN' ],
            [ 'ROLE_USER' ],
        ];
    }

    /**
     * @test
     * @dataProvider doesNotContainRoleDataProvider
     *
     * @param string[] $roles
     * @param string   $role
     */
    public function itDoesNotContainPreviouslyNotAddedRole(array $roles, string $role): void
    {
        $roles = Roles::fromArray($roles);

        $this->assertFalse($roles->contains(new Role($role)));
    }

    public function doesNotContainRoleDataProvider(): array
    {
        return [
            [ [], 'ROLE_SUPER_ADMIN' ],
            [ [], 'ROLE_USER' ],
            [ ['ROLE_USER'], 'ROLE_SUPER_ADMIN' ],
            [ ['ROLE_SUPER_ADMIN'], 'ROLE_USER' ],
            [ ['ROLE_SUPER_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'], 'ROLE_USER' ],
        ];
    }
}
