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
use IdentityAccess\Ui\Access\ChangeRoles\ChangeRolesRequest;
use IdentityAccess\Ui\Identity\ChangeEmail\ChangeEmailRequest;
use IdentityAccess\Ui\Identity\ChangePassword\ChangePasswordRequest;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;
use IdentityAccess\Ui\Identity\RegisterUser\RegisterUserRequest;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User.
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 * @ApiResource(
 *     iri="http://schema.org/Person",
 *     mercure=true,
 *     messenger="input",
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={"user:list"},
 *                 "swagger_definition_name"="list",
 *             },
 *             "openapi_context"={
 *                 "summary"="Retrieves Users.",
 *                 "description"="Retrieves the collection of Users.",
 *                 "security"={{"apiKey"={}}},
 *             },
 *         },
 *         "register"={
 *             "method"="POST",
 *             "input"=RegisterUserRequest::class,
 *             "normalization_context"={
 *                 "groups"={"user:id"},
 *                 "swagger_definition_name"="id",
 *             },
 *             "openapi_context"={
 *                 "summary"="Registers User.",
 *                 "description"="Registers new User.",
 *                 "security"={{"apiKey"={}}},
 *             },
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={"user:details"},
 *                 "swagger_definition_name"="details",
 *             },
 *             "openapi_context"={
 *                 "summary"="Retrieves User.",
 *                 "description"="Retrieves a User.",
 *                 "security"={{"apiKey"={}}},
 *             },
 *         },
 *         "changeStatus"={
 *             "method"="PUT",
 *             "path"="/users/{id}/status",
 *             "input"=ChangeUserStatusRequest::class,
 *             "output"=false,
 *             "openapi_context"={
 *                 "summary"="Updates User status.",
 *                 "description"="Enables or disables User.",
 *                 "security"={{"apiKey"={}}},
 *             },
 *         },
 *         "changeEmail"={
 *             "method"="PUT",
 *             "path"="/users/{id}/email",
 *             "input"=ChangeEmailRequest::class,
 *             "output"=false,
 *             "openapi_context"={
 *                 "summary"="Updates User email.",
 *                 "description"="Updates User email address.",
 *                 "security"={{"apiKey"={}}},
 *             },
 *         },
 *         "changePassword"={
 *             "method"="PUT",
 *             "path"="/users/{id}/password",
 *             "input"=ChangePasswordRequest::class,
 *             "output"=false,
 *             "openapi_context"={
 *                 "summary"="Updates User password.",
 *                 "description"="Updates User password.",
 *                 "security"={{"apiKey"={}}},
 *             },
 *         },
 *         "changeRoles"={
 *             "method"="PUT",
 *             "path"="/users/{id}/roles",
 *             "input"=ChangeRolesRequest::class,
 *             "output"=false,
 *             "openapi_context"={
 *                 "summary"="Updates User roles.",
 *                 "description"="Updates User roles.",
 *                 "security"={{"apiKey"={}}},
 *             },
 *         },
 *     },
 * )
 */
class User implements UserInterface, EnableableUserInterface, SecurityUserInterface, SerializableReadModel
{
    private UuidInterface $id;

    private ?string $email;

    private ?string $hashedPassword;

    private ?array $roles;

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
        $this->email = null === $email ? null : $email->toString();
        $this->hashedPassword = null === $hashedPassword ? null : $hashedPassword->toString();
        $this->roles = null === $roles ? null : $roles->toArray();
        $this->enabled = $enabled;
        $this->registeredById = null === $registeredById ? null : Uuid::fromString($registeredById->toString());
        $this->dateRegistered = $dateRegistered;
    }

    /**
     * User ID.
     *
     * @Groups({"user:id", "user:details", "user:list"})
     */
    public function getId(): string
    {
        return $this->id->toString();
    }

    public function setEmail(Email $email)
    {
        $this->email = $email->toString();

        return $this;
    }

    /**
     * User email.
     *
     * @ApiProperty(iri="http://schema.org/email")
     * @Groups({"user:details", "user:list"})
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setHashedPassword(HashedPassword $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword->toString();

        return $this;
    }

    /**
     * @ApiProperty(readable=false, writable=false)
     */
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
     * User roles.
     *
     * @ApiProperty()
     * @Groups({"user:details", "user:list"})
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * Account status.
     *
     * @ApiProperty()
     * @Groups({"user:details", "user:list"})
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * User which registered this user.
     *
     * @ApiProperty()
     * @Groups({"user:details"})
     */
    public function getRegisteredById(): ?string
    {
        return null === $this->registeredById ? null : $this->registeredById->toString();
    }

    /**
     * Date when user was registered.
     *
     * @ApiProperty()
     * @Groups({"user:details", "user:list"})
     */
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
