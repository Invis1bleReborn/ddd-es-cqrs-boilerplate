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

namespace IdentityAccess\Ui\Identity\ChangePassword;

use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePasswordRequest.
 */
final class ChangePasswordRequest implements RequestInterface
{
    /**
     * @UserPassword
     * @Assert\Length(min=6)
     */
    public ?string $currentPassword;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     */
    public ?string $password;

    /**
     * @Assert\IdenticalTo(propertyPath="password")
     */
    public ?string $passwordConfirmation;

    public function __construct(
        ?string $currentPassword = null,
        ?string $password = null,
        ?string $passwordConfirmation = null
    ) {
        $this->currentPassword = $currentPassword;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }
}
