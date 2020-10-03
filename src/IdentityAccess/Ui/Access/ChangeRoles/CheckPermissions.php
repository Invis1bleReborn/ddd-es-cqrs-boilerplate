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

use IdentityAccess\Application\Command\Access\ChangeRoles\ChangeRolesCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessCheckerInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;

/**
 * Class CheckPermissions.
 */
class CheckPermissions extends ChangeRolesRequestTransformerDecorator
{
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        ChangeRolesRequestTransformerInterface $transformer,
        AccessCheckerInterface $accessChecker
    ) {
        parent::__construct($transformer);

        $this->accessChecker = $accessChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ChangeRolesRequest $request, UserInterface $user): ChangeRolesCommand
    {
        if (!$this->accessChecker->isGranted('change', $user, 'roles')) {
            throw new AccessDeniedException('Roles change denied.');
        }

        return ($this->transformer)($request, $user);
    }
}
