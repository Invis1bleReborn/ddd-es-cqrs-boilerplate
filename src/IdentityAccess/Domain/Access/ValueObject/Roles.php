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

namespace IdentityAccess\Domain\Access\ValueObject;

/**
 * Class Roles.
 */
final class Roles
{
    /**
     * @var Role[]
     */
    private array $values;

    private function __construct(Role ...$roles)
    {
        $this->values = $roles;
    }

    /**
     * @param string[] $roles
     */
    public static function fromArray(array $roles): self
    {
        $uniqueRoles = [];

        foreach ($roles as $role) {
            $uniqueRoles[$role] = new Role($role);
        }

        return new self(...array_values($uniqueRoles));
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return array_map(fn (Role $role) => $role->getValue(), $this->values);
    }

    public function equals(self $roles): bool
    {
        if (count($roles->values) !== count($this->values)) {
            return false;
        }

        if (empty(array_diff($roles->values, $this->values))) {
            return true;
        }

        return false;
    }

    public function contains(Role $role): bool
    {
        foreach ($this->values as $existingRole) {
            if ($existingRole->equals($role)) {
                return true;
            }
        }

        return false;
    }
}
