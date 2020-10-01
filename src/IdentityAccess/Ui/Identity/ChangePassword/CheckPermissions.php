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

use IdentityAccess\Application\Command\Identity\ChangePassword\ChangePasswordCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessCheckerInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;
use IdentityAccess\Ui\Identity\ChangePassword\ChangePasswordRequest;

/**
 * Class CheckPermissions.
 */
class CheckPermissions extends ChangePasswordRequestTransformerDecorator
{
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        ChangePasswordRequestTransformerInterface $transformer,
        AccessCheckerInterface $accessChecker
    ) {
        parent::__construct($transformer);

        $this->accessChecker = $accessChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ChangePasswordRequest $request, UserInterface $user): ChangePasswordCommand
    {
        if (!$this->accessChecker->isGranted('change', $user)) {
            throw new AccessDeniedException('User enabling denied.');
        }

        return ($this->transformer)($request, $user);
    }
}
