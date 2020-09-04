<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\ChangeUserStatus;

use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangeUserStatusRequest
 *
 * @package IdentityAccess\Ui\Identity\ChangeUserStatus
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
