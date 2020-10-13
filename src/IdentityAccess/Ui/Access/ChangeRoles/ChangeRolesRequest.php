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

namespace IdentityAccess\Ui\Access\ChangeRoles;

use ApiPlatform\Core\Annotation\ApiProperty;
use Common\Shared\Ui\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangeRolesRequest.
 */
final class ChangeRolesRequest implements RequestInterface
{
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

    public function __construct(?array $roles = null)
    {
        $this->roles = $roles;
    }
}
