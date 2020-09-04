<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Access\CreateToken;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Ui\ValidatorAwareRequestTransformer;
use IdentityAccess\Application\Command\Access\CreateToken\CreateTokenCommand;
use IdentityAccess\Application\Query\Identity\UserProviderInterface;
use IdentityAccess\Ui\Access\UserCheckerInterface;

/**
 * Class CreateTokenRequestTransformer
 *
 * @package IdentityAccess\Ui\Access\CreateToken
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
