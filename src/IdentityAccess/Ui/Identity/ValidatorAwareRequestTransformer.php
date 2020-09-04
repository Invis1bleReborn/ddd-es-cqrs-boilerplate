<?php

declare(strict_types=1);

namespace IdentityAccess\Ui\Identity;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Common\Shared\Ui\ValidatorAwareRequestTransformer as BaseValidatorAwareRequestTransformer;

/**
 * Class ValidatorAwareRequestTransformer
 *
 * @package IdentityAccess\Ui\Identity
 */
abstract class ValidatorAwareRequestTransformer extends BaseValidatorAwareRequestTransformer
{
    private AuthenticatedUserProviderInterface $authenticatedUserProvider;

    public function __construct(
        ValidatorInterface $validator,
        AuthenticatedUserProviderInterface $authenticatedUserProvider
    )
    {
        parent::__construct($validator);

        $this->authenticatedUserProvider = $authenticatedUserProvider;
    }

    protected function getAuthenticatedUserId(): ?string
    {
        $user = $this->authenticatedUserProvider->getUser();

        if (null === $user) {
            return null;
        }

        return $user->getId();
    }

}
