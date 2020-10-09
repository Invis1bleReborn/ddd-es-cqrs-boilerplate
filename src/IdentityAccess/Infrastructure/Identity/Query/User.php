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
use Common\Shared\Ui\IdAwareView;
use IdentityAccess\Application\Query\Identity\EnableableUserInterface;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Domain\Access\ValueObject\Roles;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\HashedPassword;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Ui\Access\ChangeRoles\ChangeRolesRequest;
use IdentityAccess\Ui\Identity\ChangeEmail\ChangeEmailRequest;
use IdentityAccess\Ui\Identity\ChangePassword\ChangePasswordRequest;
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
 *         "register"={
 *             "method"="POST",
 *             "status"=201,
 *             "input"=RegisterUserRequest::class,
 *             "output"=IdAwareView::class,
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"details"}},
 *         },
 *         "changeStatus"={
 *             "method"="PUT",
 *             "path"="/users/{id}/status",
 *             "input"=ChangeUserStatusRequest::class,
 *             "output"=false,
 *         },
 *         "changeEmail"={
 *             "method"="PUT",
 *             "path"="/users/{id}/email",
 *             "input"=ChangeEmailRequest::class,
 *             "output"=false,
 *         },
 *         "changePassword"={
 *             "method"="PUT",
 *             "path"="/users/{id}/password",
 *             "input"=ChangePasswordRequest::class,
 *             "output"=false,
 *         },
 *         "changeRoles"={
 *             "method"="PUT",
 *             "path"="/users/{id}/roles",
 *             "input"=ChangeRolesRequest::class,
 *             "output"=false,
 *         },
 *     },
 * )
 */
class User implements UserInterface, EnableableUserInterface, SecurityUserInterface, SerializableReadModel
{
    private UuidInterface $id;

    private ?Email $email;

    private ?HashedPassword $hashedPassword;

    private ?Roles $roles;

    private ?bool $enabled;

    private ?UuidInterface $registeredById;

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

    public function setEmail(Email $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @ApiProperty(iri="http://schema.org/email")
     */
    public function getEmail(): ?string
    {
        return null === $this->email ? null : $this->email->toString();
    }

    public function setHashedPassword(HashedPassword $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;

        return $this;
    }

    /**
     * @ApiProperty(iri="http://schema.org/accessCode")
     */
    public function getHashedPassword(): ?string
    {
        return null === $this->hashedPassword ? null : $this->hashedPassword->toString();
    }

    public function setRoles(Roles $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles(): ?array
    {
        return null === $this->roles ? null : $this->roles->toArray();
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
        return $this->getHashedPassword();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->getEmail();
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
            'email' => null === $this->email ? null : $this->email->toString(),
            'hashedPassword' => null === $this->hashedPassword ? null : $this->hashedPassword->toString(),
            'roles' => null === $this->roles ? null : $this->roles->toArray(),
            'enabled' => $this->enabled,
            'registeredById' => null === $this->registeredById ? null : $this->registeredById->toString(),
            'dateRegistered' => null === $this->dateRegistered ? null : $this->dateRegistered->toString(),
        ];
    }
}
