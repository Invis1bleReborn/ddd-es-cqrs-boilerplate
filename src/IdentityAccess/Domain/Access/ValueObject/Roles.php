<?php

declare(strict_types=1);

namespace IdentityAccess\Domain\Access\ValueObject;

/**
 * Class Roles
 *
 * @package IdentityAccess\Domain\Access\ValueObject
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
     *
     * @return self
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
        return array_map(fn(Role $role) => $role->getValue(), $this->values);
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
        foreach ($this->values as $role_) {
            if ($role_->equals($role)) {
                return true;
            }
        }

        return false;
    }

}
