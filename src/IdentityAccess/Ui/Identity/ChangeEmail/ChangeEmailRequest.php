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

namespace IdentityAccess\Ui\Identity\ChangeEmail;

use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangeEmailRequest.
 */
final class ChangeEmailRequest implements RequestInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public ?string $email;

    public function __construct(?string $email = null)
    {
        $this->email = $email;
    }
}
