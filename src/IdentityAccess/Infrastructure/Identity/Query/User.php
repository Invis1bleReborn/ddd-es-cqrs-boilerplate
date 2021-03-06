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

namespace IdentityAccess\Infrastructure\Identity\Query;

use Assert\AssertionFailedException;
use Broadway\ReadModel\SerializableReadModel;
use Common\Shared\Domain\Exception\DateTimeException;
use Common\Shared\Domain\ValueObject\DateTime;
use IdentityAccess\Application\Query\Identity\EnableableUserInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

/**
 * User.
 *
 * @see https://schema.org/Person Documentation on Schema.org
 */
class User implements UserInterface, EnableableUserInterface, SecurityUserInterface, SerializableReadModel
{
    /**
     * User ID.
     */
    private UuidInterface $id;

    /**
     * User email.
     */
    private ?string $email;

    private ?string $hashedPassword;

    /**
     * User roles.
     *
     * @var string[]|null
     */
    private ?array $roles;

    /**
     * Account status.
     */
    private ?bool $enabled;

    /**
     * User which registered this user.
     */
    private ?UuidInterface $registeredById;

    /**
     * Date when user was registered.
     */
    private ?DateTime $dateRegistered;

    public function __construct(
        UserId $id,
        ?Email $email = null,
        ?HashedPassword $hashedPassword = null,
        ?Roles $roles = null,
        ?bool $enabled = null,
        ?UserId $registeredById = null,
        ?DateTime $dateRegistered = null
    ) {
        $this->id = Uuid::fromString($id->toString());
        $this->email = null === $email ? null : $email->toString();
        $this->hashedPassword = null === $hashedPassword ? null : $hashedPassword->toString();
        $this->roles = null === $roles ? null : $roles->toArray();
        $this->enabled = $enabled;
        $this->registeredById = null === $registeredById ? null : Uuid::fromString($registeredById->toString());
        $this->dateRegistered = $dateRegistered;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function setEmail(Email $email)
    {
        $this->email = $email->toString();

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setHashedPassword(HashedPassword $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword->toString();

        return $this;
    }

    public function getHashedPassword(): ?string
    {
        return $this->hashedPassword;
    }

    public function setRoles(Roles $roles)
    {
        $this->roles = $roles->toArray();

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getRegisteredById(): ?string
    {
        return null === $this->registeredById ? null : $this->registeredById->toString();
    }

    public function getDateRegistered(): ?\DateTimeImmutable
    {
        if (null === $this->dateRegistered) {
            return null;
        }

        $dateRegistered = $this->dateRegistered->toNative();
        /* @var $dateRegistered \DateTimeImmutable */

        return $dateRegistered;
    }

    public function getPassword(): ?string
    {
        return $this->hashedPassword;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // no op
    }

    /**
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data)
    {
        return new self(
            UserId::fromString($data['id']),
            isset($data['email']) ? Email::fromString($data['email']) : null,
            isset($data['hashedPassword']) ? HashedPassword::fromString($data['hashedPassword']) : null,
            isset($data['roles']) ? Roles::fromArray($data['roles']) : null,
            $data['enabled'] ?? null,
            isset($data['registeredById']) ? UserId::fromString($data['registeredById']) : null,
            isset($data['dateRegistered']) ? DateTime::fromString($data['dateRegistered']) : null
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'email' => $this->email,
            'hashedPassword' => $this->hashedPassword,
            'roles' => $this->roles,
            'enabled' => $this->enabled,
            'registeredById' => null === $this->registeredById ? null : $this->registeredById->toString(),
            'dateRegistered' => null === $this->dateRegistered ? null : $this->dateRegistered->toString(),
        ];
    }
}
