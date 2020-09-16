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

namespace IdentityAccess\Domain\Access;

use IdentityAccess\Domain\Access\ValueObject\Role;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Infrastructure\Access\RoleHierarchy;
use PHPUnit\Framework\TestCase;

/**
 * Class RoleHierarchyTest
 *
 * @package IdentityAccess\Domain\Access
 */
class RoleHierarchyTest extends TestCase
{
    /**
     * @test
     * @dataProvider reachableRolesProvider
     *
     * @param string[][]|string[] $roleHierarchy
     * @param string[]            $roles
     * @param string[]            $expectedReachableRoles
     */
    public function testReachableRoles(array $roleHierarchy, array $roles, array $expectedReachableRoles): void
    {
        $reachableRoles = (new RoleHierarchy(new \Symfony\Component\Security\Core\Role\RoleHierarchy($roleHierarchy)))
            ->reachableRoles(Roles::fromArray($roles));

        $this->assertTrue($reachableRoles->equals(Roles::fromArray($expectedReachableRoles)));
    }

    /**
     * @test
     * @dataProvider reachableRolesProvider
     *
     * @param string[][]|string[] $roleHierarchy
     * @param string[]            $roles
     * @param string[]            $expectedReachableRoles
     * @param string[]            $expectedNotReachableRoles
     */
    public function testRoleReachable(
        array $roleHierarchy,
        array $roles,
        array $expectedReachableRoles,
        array $expectedNotReachableRoles
    ): void
    {
        $roleHierarchy = new RoleHierarchy(new \Symfony\Component\Security\Core\Role\RoleHierarchy($roleHierarchy));
        $roles = Roles::fromArray($roles);

        foreach ($expectedReachableRoles as $expectedReachableRole) {
            $this->assertTrue($roleHierarchy->roleReachable($roles, new Role($expectedReachableRole)));
        }

        foreach ($expectedNotReachableRoles as $expectedNotReachableRole) {
            $this->assertFalse($roleHierarchy->roleReachable($roles, new Role($expectedNotReachableRole)));
        }

    }

    /**
     * @return array[][] 0: role hierarchy
     *                   1: roles
     *                   2: expected reachable roles
     *                   3: expected not reachable roles
     */
    public function reachableRolesProvider(): array
    {
        return [
            [
                [],
                [],
                [],
                ['ROLE_SUPER_ADMIN', 'ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'],
            ],
            [
                [],
                ['ROLE_USER'],
                ['ROLE_USER'],
                ['ROLE_SUPER_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'],
            ],
            [
                ['ROLE_SUPER_ADMIN' => ['ROLE_ALLOWED_TO_SWITCH']],
                ['ROLE_USER'],
                ['ROLE_USER'],
                ['ROLE_SUPER_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'],
            ],
            [
                ['ROLE_SUPER_ADMIN' => [], 'ROLE_USER' => []],
                ['ROLE_SUPER_ADMIN'],
                ['ROLE_SUPER_ADMIN'],
                ['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'],
            ],
            [
                ['ROLE_SUPER_ADMIN' => [], 'ROLE_USER' => []],
                ['ROLE_USER'],
                ['ROLE_USER'],
                ['ROLE_SUPER_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'],
            ],
            [
                ['ROLE_SUPER_ADMIN' => ['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH']],
                ['ROLE_USER'],
                ['ROLE_USER'],
                ['ROLE_SUPER_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'],
            ],
            [
                ['ROLE_SUPER_ADMIN' => ['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH']],
                ['ROLE_SUPER_ADMIN'],
                ['ROLE_SUPER_ADMIN', 'ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'],
                [],
            ],
        ];
    }

}
