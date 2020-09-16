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

namespace IdentityAccess\Ui\Identity\RegisterUser;

use IdentityAccess\Application\Command\Identity\RegisterUser\RegisterUserCommand;
use IdentityAccess\Ui\Access\AccessCheckerInterface;
use IdentityAccess\Ui\Access\AccessDeniedException;

/**
 * Class CheckPermissions
 *
 * @package IdentityAccess\Ui\Identity\RegisterUser
 */
class CheckPermissions extends RegisterUserRequestTransformerDecorator
{
    private AccessCheckerInterface $accessChecker;

    public function __construct(
        RegisterUserRequestTransformerInterface $transformer,
        AccessCheckerInterface $accessChecker
    )
    {
        parent::__construct($transformer);

        $this->accessChecker = $accessChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(RegisterUserRequest $request): RegisterUserCommand
    {
        if (!$this->accessChecker->isGranted('register', 'user')) {
            throw new AccessDeniedException('User registration denied.');
        }

        return ($this->transformer)($request);
    }

}
