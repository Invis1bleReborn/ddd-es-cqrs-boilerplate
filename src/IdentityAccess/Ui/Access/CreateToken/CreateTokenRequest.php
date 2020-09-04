<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access\CreateToken;

use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateTokenRequest
 *
 * @package IdentityAccess\Ui\Access\CreateToken
 */
final class CreateTokenRequest implements RequestInterface
{
    /**
     * @Assert\NotBlank
     */
    public ?string $username;

    /**
     * @Assert\NotBlank
     */
    public ?string $password;

    public function __construct(
        ?string $username = null,
        ?string $password = null
    )
    {
        $this->username = $username;
        $this->password = $password;
    }

}
