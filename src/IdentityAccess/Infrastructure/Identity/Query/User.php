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

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
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
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;
use IdentityAccess\Ui\Identity\RegisterUser\RegisterUserRequest;
use IdentityAccess\Ui\Identity\UserView;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

/**
 * User.
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 * @ApiResource(
 *     iri="http://schema.org/Person",
 *     mercure=true,
 *     messenger="input",
 *     output=UserView::class,
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"list"}},
 *         },
 *         "create"={
 *             "method"="POST",
 *             "status"=201,
 *             "input"=RegisterUserRequest::class,
 *             "output"=false,
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"details"}},
 *         },
 *         "changeStatus"={
 *             "method"="PUT",
 *             "path"="/user/{id}/status",
 *             "input"=ChangeUserStatusRequest::class,
 *             "output"=false,
 *         },
 *     },
 * )
 */
class User implements UserInterface, EnableableUserInterface, SecurityUserInterface, SerializableReadModel
{
    private UuidInterface $id;

    private Email $email;

    private HashedPassword $hashedPassword;

    private Roles $roles;

    private bool $enabled;

    private ?UuidInterface $registeredById;

    private DateTime $dateRegistered;

    public function __construct(
        UserId $id,
        Email $email,
        HashedPassword $hashedPassword,
        Roles $roles,
        bool $enabled,
        ?UserId $registeredById,
        DateTime $dateRegistered
    ) {
        $this->id = Uuid::fromString($id->toString());
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
        $this->roles = $roles;
        $this->enabled = $enabled;
        $this->registeredById = null === $registeredById ? null : Uuid::fromString($registeredById->toString());
        $this->dateRegistered = $dateRegistered;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function changeEmail(Email $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @ApiProperty(iri="http://schema.org/email")
     */
    public function getEmail(): string
    {
        return $this->email->toString();
    }

    /**
     * @ApiProperty(iri="http://schema.org/accessCode")
     */
    public function getHashedPassword(): string
    {
        return $this->hashedPassword->toString();
    }

    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function isEnabled(): bool
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

    public function getDateRegistered(): \DateTimeImmutable
    {
        $dateRegistered = $this->dateRegistered->toNative();
        /* @var $dateRegistered \DateTimeImmutable */

        return $dateRegistered;
    }

    public function getPassword(): string
    {
        return $this->hashedPassword->toString();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email->toString();
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
            Email::fromString($data['email']),
            HashedPassword::fromString($data['hashedPassword']),
            Roles::fromArray($data['roles']),
            $data['enabled'],
            $data['registeredById'] ?? null,
            DateTime::fromString($data['dateRegistered'])
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'email' => $this->email->toString(),
            'hashedPassword' => $this->hashedPassword->toString(),
            'roles' => $this->roles->toArray(),
            'enabled' => $this->enabled,
            'registeredById' => null === $this->registeredById ? null : $this->registeredById->toString(),
            'dateRegistered' => $this->dateRegistered->toString(),
        ];
    }
}
