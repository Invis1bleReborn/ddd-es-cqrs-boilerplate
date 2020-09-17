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

namespace IdentityAccess\Ui\Access\CreateToken;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Ui\ValidatorAwareRequestTransformer;
use IdentityAccess\Application\Command\Access\CreateToken\CreateTokenCommand;
use IdentityAccess\Application\Query\Identity\UserProviderInterface;
use IdentityAccess\Ui\Access\UserCheckerInterface;

/**
 * Class CreateTokenRequestTransformer.
 */
class CreateTokenRequestTransformer extends ValidatorAwareRequestTransformer implements
    CreateTokenRequestTransformerInterface
{
    private UserProviderInterface $userProvider;

    private UserCheckerInterface $userChecker;

    public function __construct(
        ValidatorInterface $validator,
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker
    )
    {
        parent::__construct($validator);

        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(CreateTokenRequest $request): CreateTokenCommand
    {
        $this->validate($request);

        $user = $this->userProvider->loadUserByUsername($request->username);

        ($this->userChecker)($user);

        return new CreateTokenCommand(
            $user,
            $request->password
        );
    }
}
