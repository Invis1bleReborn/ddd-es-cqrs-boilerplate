<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity\RegisterUser;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Domain\ValueObject\UuidGeneratorInterface;
use IdentityAccess\Application\Command\Identity\RegisterUser\RegisterUserCommand;
use IdentityAccess\Ui\Identity\AuthenticatedUserProviderInterface;
use IdentityAccess\Ui\Identity\ValidatorAwareRequestTransformer;

/**
 * Class RegisterUserRequestTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
class RegisterUserRequestTransformer extends ValidatorAwareRequestTransformer implements
    RegisterUserRequestTransformerInterface
{
    private UuidGeneratorInterface $uuidGenerator;

    public function __construct(
        ValidatorInterface $validator,
        UuidGeneratorInterface $uuidGenerator,
        AuthenticatedUserProviderInterface $authenticatedUserProvider
    )
    {
        parent::__construct(
            $validator,
            $authenticatedUserProvider
        );

        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(RegisterUserRequest $request): RegisterUserCommand
    {
        $this->validate($request);

        return new RegisterUserCommand(
            ($this->uuidGenerator)(),
            $request->email,
            $request->password,
            $request->enabled,
            array_merge(array_intersect($request->roles, ['ROLE_USER']), ['ROLE_USER']),
            $this->getAuthenticatedUserId()
        );
    }

}
