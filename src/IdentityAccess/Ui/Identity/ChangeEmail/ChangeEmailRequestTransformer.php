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
use IdentityAccess\Ui\Identity\ValidatorAwareRequestTransformer;

/**
 * Class ChangeEmailRequestTransformer.
 */
class ChangeEmailRequestTransformer extends ValidatorAwareRequestTransformer implements
    ChangeEmailRequestTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ChangeEmailRequest $request, UserInterface $user): ChangeEmailCommand
    {
        $this->validate($request);

        return new ChangeEmailCommand(
            $user->getId(),
            $request->email,
            $this->getAuthenticatedUserId()
        );
    }
}
