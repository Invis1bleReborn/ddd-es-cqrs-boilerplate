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

use IdentityAccess\Application\Command\Identity\ChangeEmail\ChangeEmailCommand;
use IdentityAccess\Application\Query\Identity\UserInterface;
use IdentityAccess\Ui\Access\AccessCheckerInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;

/**
 * Class CheckPermissions.
 */
class CheckPermissions extends ChangeEmailRequestTransformerDecorator
{
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        ChangeEmailRequestTransformerInterface $transformer,
        AccessCheckerInterface $accessChecker
    ) {
        parent::__construct($transformer);

        $this->accessChecker = $accessChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ChangeEmailRequest $request, UserInterface $user): ChangeEmailCommand
    {
        if (!$this->accessChecker->isGranted('change', $user, 'email')) {
            throw new AccessDeniedException('Email change denied.');
        }

        return ($this->transformer)($request, $user);
    }
}
