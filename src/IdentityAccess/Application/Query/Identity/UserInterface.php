<?php

declare(strict_types=1);

namespace IdentityAccess\Application\Query\Identity;

/**
 * Interface UserInterface
 *
 * @package IdentityAccess\Application\Query\Identity
 */
interface UserInterface
{
    public function getId(): string;

    public function getEmail(): string;

    public function getHashedPassword(): string;

    public function getRoles(): array;

    public function isEnabled(): bool;

    public function getRegisteredById(): ?string;

    public function getDateRegistered(): \DateTimeImmutable;

}
