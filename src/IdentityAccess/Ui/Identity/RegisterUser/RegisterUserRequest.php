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

namespace IdentityAccess\Ui\Identity\RegisterUser;

use ApiPlatform\Core\Annotation\ApiProperty;
use Common\Shared\Infrastructure\Validator\Constraints\UniqueDto;
use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterUserRequest.
 *
 * @UniqueDto(
 *     entityClass="IdentityAccess\Infrastructure\Identity\Query\User",
 *     fieldMapping={"email": "email"}
 * )
 */
final class RegisterUserRequest implements RequestInterface
{
    /**
     * User email.
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    public ?string $email;

    /**
     * User password.
     *
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     */
    public ?string $password;

    /**
     * Password confirmation.
     *
     * @Assert\IdenticalTo(propertyPath="password")
     */
    public ?string $passwordConfirmation;

    /**
     * Account status.
     *
     * @Assert\NotNull
     */
    public ?bool $enabled;

    /**
     * User roles.
     *
     * @Assert\Choice(callback={"IdentityAccess\Domain\Access\ValueObject\Role", "toArray"}, multiple=true)
     * @Assert\Unique
     * @ApiProperty(
     *     example={"ROLE_USER"},
     *     default={},
     *     openapiContext={
     *         "type"="array",
     *         "items"={
     *             "type"="string",
     *             "example"="ROLE_USER",
     *         },
     *     },
     * )
     */
    public ?array $roles;

    public function __construct(
        ?string $email = null,
        ?string $password = null,
        ?string $passwordConfirmation = null,
        ?bool $enabled = null,
        ?array $roles = null
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
        $this->enabled = $enabled;
        $this->roles = $roles;
    }
}
