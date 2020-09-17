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

namespace IdentityAccess\Ui\Identity\ChangeUserStatus;

use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangeUserStatusRequest
 */
final class ChangeUserStatusRequest implements RequestInterface
{
    /**
     * @Assert\NotNull
     */
    public ?bool $enabled;

    public function __construct(?bool $enabled = null)
    {
        $this->enabled = $enabled;
    }
}
