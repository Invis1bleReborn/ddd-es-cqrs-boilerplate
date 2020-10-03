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

use ApiPlatform\Core\Validator\Exception\ValidationException;
use Assert\AssertionFailedException;
use IdentityAccess\Application\Command\Access\ChangeRoles\ChangeRolesCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;

/**
 * Class ChangeRolesRequestTransformer.
 */
interface ChangeRolesRequestTransformerInterface
{
    /**
     * @throws AccessDeniedException
     * @throws ValidationException
     * @throws AssertionFailedException
     */
    public function __invoke(ChangeRolesRequest $request, UserInterface $user): ChangeRolesCommand;
}
