<?php

declare(strict_types=1);

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IdentityAccess\Ui\Identity\EnableUser;

use IdentityAccess\Application\Command\Identity\EnableUser\EnableUserCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessCheckerInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\ChangeUserStatus\ChangeUserStatusRequest;

/**
 * Class CheckPermissions
 *
 * @package IdentityAccess\Ui\Identity\EnableUser
 */
class CheckPermissions extends EnableUserRequestTransformerDecorator
{
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        EnableUserRequestTransformerInterface $transformer,
        AccessCheckerInterface $accessChecker
    )
    {
        parent::__construct($transformer);

        $this->accessChecker = $accessChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ChangeUserStatusRequest $request, UserInterface $user): EnableUserCommand
    {
        if (!$this->accessChecker->isGranted('enable', $user)) {
            throw new AccessDeniedException('User enabling denied.');
        }

        return ($this->transformer)($request, $user);
    }

}
